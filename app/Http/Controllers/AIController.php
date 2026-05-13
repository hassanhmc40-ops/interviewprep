<?php

namespace App\Http\Controllers;

use App\Models\Concept;
use App\Models\GeneratedQuestion;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Http;

class AIController extends Controller
{
    public function generate(Concept $concept): JsonResponse
    {
        if ($concept->user_id !== auth()->id()) {
            abort(403);
        }

        try {
            $response = Http::timeout(30)
                ->withToken(config('services.groq.api_key'))
                ->post('https://api.groq.com/openai/v1/chat/completions', [
                    'model' => 'mixtral-8x7b-32768',
                    'messages' => [
                        [
                            'role' => 'user',
                            'content' => "Generate 5 realistic technical interview questions about:\n\nTitle: {$concept->title}\n\nExplanation: {$concept->explanation}\n\nReturn ONLY a JSON array with 5 questions, no preamble."
                        ]
                    ],
                    'temperature' => 0.7,
                    'max_tokens' => 2048
                ]);

            if ($response->failed()) {
                throw new \Exception('API request failed');
            }

            $data = $response->json();
            $content = $data['choices'][0]['message']['content'];

            $questions = json_decode($content, true);

            if (!is_array($questions) || count($questions) === 0) {
                throw new \Exception('Invalid response format');
            }

            foreach ($questions as $question) {
                GeneratedQuestion::create([
                    'concept_id' => $concept->id,
                    'question' => $question,
                    'generated_at' => now()
                ]);
            }

            return response()->json([
                'success' => true,
                'questions' => $questions,
                'count' => count($questions)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate questions. Please try again.'
            ], 500);
        }
    }

    public function destroy(GeneratedQuestion $generatedQuestion): RedirectResponse
    {
        if ($generatedQuestion->concept->user_id !== auth()->id()) {
            abort(403);
        }

        $generatedQuestion->delete();

        return back()->with('success', 'Generation deleted successfully.');
    }
}