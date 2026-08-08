<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiChatService
{
    public function generateResponse(string $question): string
    {
        $key = config('services.gemini.key');
        $endpoint = config('services.gemini.endpoint');
        $model = config('services.gemini.model');
        $type = config('services.gemini.type', 'google');

        if (empty($key) || empty($endpoint)) {
            Log::warning('Gemini API key or endpoint is not configured.');
            return 'Le service de conversation n\'est pas configuré. Veuillez contacter l\'administrateur.';
        }

        try {
            if ($type === 'google') {
                $payload = [
                    'contents' => [
                        [
                            'parts' => [
                                [
                                    'text' => $question,
                                ],
                            ],
                        ],
                    ],
                ];

                if (!str_contains($endpoint, ':generateContent')) {
                    $endpoint = rtrim($endpoint, '/') . '/models/' . trim($model, '/') . ':generateContent';
                }

                $response = Http::timeout(30)
                    ->acceptJson()
                    ->withHeaders([
                        'x-goog-api-key' => $key,
                    ])
                    ->post($endpoint, $payload);
            } else {
                $payload = [
                    'model' => $model,
                    'messages' => [
                        [
                            'role' => 'user',
                            'content' => $question,
                        ],
                    ],
                ];

                $response = Http::timeout(30)
                    ->acceptJson()
                    ->withToken($key)
                    ->post($endpoint, $payload);
            }

            if ($response->failed()) {
                Log::error('Gemini API request failed.', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);

                return 'Le service de réponse automatique est temporairement indisponible.';
            }

            $data = $response->json();
            $text = $this->parseResponse($data);

            if ($text === null) {
                Log::warning('Gemini API returned an unexpected payload.', ['payload' => $data]);
                return 'Le service a répondu, mais le format de la réponse est inattendu.';
            }

            return trim($text);
        } catch (\Exception $e) {
            Log::error('Gemini API exception.', ['message' => $e->getMessage()]);
            return 'Le service Gemini a rencontré une erreur. Veuillez réessayer plus tard.';
        }
    }

    private function parseResponse(array $data): ?string
    {
        // Newer Gemini responses may include a `candidates` array with
        // `content.parts[0].text` (observed in v1beta responses).
        if (isset($data['candidates'][0]['content']['parts'][0]['text'])) {
            return $data['candidates'][0]['content']['parts'][0]['text'];
        }

        // Some responses nest the `content` under `response`.
        if (isset($data['response']['candidates'][0]['content']['parts'][0]['text'])) {
            return $data['response']['candidates'][0]['content']['parts'][0]['text'];
        }

        if (isset($data['reply']['content'][0]['text'])) {
            return $data['reply']['content'][0]['text'];
        }

        if (isset($data['output']['text'])) {
            return $data['output']['text'];
        }

        if (isset($data['message']['content'])) {
            return is_string($data['message']['content']) ? $data['message']['content'] : null;
        }

        if (isset($data['choices'][0]['message']['content'])) {
            return is_string($data['choices'][0]['message']['content']) ? $data['choices'][0]['message']['content'] : null;
        }

        if (isset($data['choices'][0]['text'])) {
            return $data['choices'][0]['text'];
        }

        if (isset($data['candidates'][0]['content'][0]['text'])) {
            return $data['candidates'][0]['content'][0]['text'];
        }

        if (isset($data['response']['candidates'][0]['content'][0]['text'])) {
            return $data['response']['candidates'][0]['content'][0]['text'];
        }

        if (isset($data['candidates'][0]['content'][0]['text'])) {
            return $data['candidates'][0]['content'][0]['text'];
        }

        return null;
    }
}
