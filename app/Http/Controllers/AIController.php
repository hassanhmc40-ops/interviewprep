<?php

namespace App\Http\Controllers;

use App\Models\Concept;
use App\Models\GeneratedQuestion;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

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

        $apiKey = config('services.groq.api_key');

        if (empty($apiKey)) {
            Log::error('Groq API key is missing');
            return back()->with('error', 'AI API key is not configured. Please contact support.');
        }

        try {
            Log::info('Starting question generation for concept: ' . $concept->id);

            $response = Http::timeout(60)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $apiKey,
                    'Content-Type' => 'application/json',
                ])
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

            Log::info('Groq API response status: ' . $response->status());

            if ($response->failed()) {
                $errorBody = $response->body();
                Log::error('Groq API failed: ' . $errorBody);
                throw new \Exception('API request failed with status: ' . $response->status() . ' - ' . $errorBody);
            }

            $data = $response->json();
            Log::info('Groq API response data keys: ' . json_encode(array_keys($data)));

            if (!isset($data['choices'][0]['message']['content'])) {
                Log::error('Invalid Groq API response structure: ' . json_encode($data));
                throw new \Exception('Invalid response from AI service');
            }

            $content = $data['choices'][0]['message']['content'];
            Log::info('AI content length: ' . strlen($content));

            $questions = $this->parseQuestions($content);
            Log::info('Parsed questions count: ' . count($questions));

            if (empty($questions)) {
                Log::warning('No valid questions parsed from content: ' . substr($content, 0, 500));
                throw new \Exception('No valid questions received from AI');
            }

            foreach ($questions as $questionText) {
                GeneratedQuestion::create([
                    'concept_id' => $concept->id,
                    'question' => $questionText,
                    'generated_at' => now()
                ]);
            }

            Log::info('Successfully saved ' . count($questions) . ' questions for concept: ' . $concept->id);

            return back()->with('success', 'Generated ' . count($questions) . ' interview questions successfully.');

        } catch (\Exception $e) {
            Log::error('Question generation failed: ' . $e->getMessage());
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

IMPORTANT: Return ONLY a valid JSON array with exactly 5 questions. No preamble, no explanation, just pure JSON like this:
[\"Question 1?\", \"Question 2?\", \"Question 3?\", \"Question 4?\", \"Question 5?\"]";
    }

    /**
     * Parse questions from AI response.
     */
    private function parseQuestions(string $content): array
    {
        $content = trim($content);
        Log::debug('Raw AI content: ' . substr($content, 0, 200));

        $content = preg_replace('/^```json\s*/i', '', $content);
        $content = preg_replace('/^```\s*/i', '', $content);
        $content = preg_replace('/\s*```$/i', '', $content);

        $questions = json_decode($content, true);

        if (json_last_error() === JSON_ERROR_NONE && is_array($questions)) {
            $filtered = array_filter($questions, 'is_string');
            if (!empty($filtered)) {
                Log::info('Parsed ' . count($filtered) . ' questions from JSON');
                return array_values($filtered);
            }
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