<?php

namespace App\Http\Controllers;

use App\Models\Concept;
use App\Models\GeneratedQuestion;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Http;

class AIController extends Controller
{
    /**
     * Generate interview questions for a concept using Groq API.
     */
    public function generate(Concept $concept): RedirectResponse
    {
        if ($concept->user_id !== auth()->id()) {
            abort(403);
        }

        try {
            $response = Http::timeout(30)
                ->withToken(config('services.groq.api_key'))
                ->post('https://api.groq.com/openai/v1/chat/completions', [
                    'model' => 'llama3-70b-8192',
                    'messages' => [
                        [
                            'role' => 'user',
                            'content' => $this->buildPrompt($concept)
                        ]
                    ],
                    'temperature' => 0.7,
                    'max_tokens' => 2048
                ]);

            if ($response->failed()) {
                throw new \Exception('API request failed: ' . $response->status());
            }

            $data = $response->json();
            $content = $data['choices'][0]['message']['content'];

            $questions = $this->parseQuestions($content);

            if (empty($questions)) {
                throw new \Exception('No valid questions received from API');
            }

            foreach ($questions as $questionText) {
                GeneratedQuestion::create([
                    'concept_id' => $concept->id,
                    'question' => $questionText,
                    'generated_at' => now()
                ]);
            }

            return back()->with('success', 'Generated ' . count($questions) . ' interview questions successfully.');

        } catch (\Exception $e) {
            return back()->with('error', 'Failed to generate questions: ' . $e->getMessage());
        }
    }

    /**
     * Delete a generated question.
     */
    public function destroy(GeneratedQuestion $generatedQuestion): RedirectResponse
    {
        if ($generatedQuestion->concept->user_id !== auth()->id()) {
            abort(403);
        }

        $generatedQuestion->delete();

        return back()->with('success', 'Question deleted successfully.');
    }

    /**
     * Build the prompt for the AI.
     */
    private function buildPrompt(Concept $concept): string
    {
        return "Generate 5 realistic Laravel technical interview questions based on the following concept:

Title: {$concept->title}

Explanation: {$concept->explanation}

Return ONLY a JSON array with exactly 5 questions, no preamble or explanation. Example format: [\"Question 1?\", \"Question 2?\", \"Question 3?\", \"Question 4?\", \"Question 5?\"]";
    }

    /**
     * Parse questions from AI response.
     */
    private function parseQuestions(string $content): array
    {
        $content = trim($content);

        $content = preg_replace('/^```json\s*/i', '', $content);
        $content = preg_replace('/^```\s*/i', '', $content);
        $content = preg_replace('/\s*```$/i', '', $content);

        $questions = json_decode($content, true);

        if (json_last_error() === JSON_ERROR_NONE && is_array($questions)) {
            return array_filter($questions, 'is_string');
        }

        $lines = array_filter(array_map('trim', explode("\n", $content)), fn($line) => !empty($line));

        $questions = [];
        foreach ($lines as $line) {
            $line = preg_replace('/^[\d.\-•]+\s*/', '', $line);
            $line = preg_replace('/^["\']+/', '', $line);
            $line = preg_replace('/["\']+$/', '', $line);
            $line = trim($line);

            if (!empty($line) && strlen($line) > 10) {
                $questions[] = $line;
            }

            if (count($questions) >= 5) {
                break;
            }
        }

        return $questions;
    }
}