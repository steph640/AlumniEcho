<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class GeminiChatbotService
{
    private string $apiKey;
    private string $apiUrl = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-pro:generateContent';

    public function __construct()
    {
        $this->apiKey = config('services.gemini.api_key');

        if (!$this->apiKey) {
            throw new Exception('Google Gemini API key not configured');
        }
    }

    /**
     * Génère une réponse basée sur la question et le contexte de la BD
     */
    public function generateResponse(string $question, array $databaseContext = []): string
    {
        try {
            // Construire le prompt avec le contexte de la BD
            $systemPrompt = $this->buildSystemPrompt($databaseContext);
            $fullPrompt = $systemPrompt . "\n\nQuestion de l'utilisateur: " . $question;

            // Appeler l'API Gemini
            $response = Http::timeout(30)->post(
                $this->apiUrl . '?key=' . $this->apiKey,
                [
                    'contents' => [
                        [
                            'parts' => [
                                ['text' => $fullPrompt]
                            ]
                        ]
                    ],
                    'generationConfig' => [
                        'temperature' => 0.7,
                        'topP' => 0.9,
                        'topK' => 40,
                        'maxOutputTokens' => 1024,
                    ],
                    'safetySettings' => [
                        [
                            'category' => 'HARM_CATEGORY_UNSPECIFIED',
                            'threshold' => 'BLOCK_NONE',
                        ],
                    ],
                ]
            );

            if ($response->failed()) {
                Log::error('Gemini API Error', [
                    'status' => $response->status(),
                    'response' => $response->json()
                ]);
                throw new Exception('Failed to get response from Gemini API');
            }

            $data = $response->json();

            // Extraire le texte de la réponse
            if (isset($data['candidates'][0]['content']['parts'][0]['text'])) {
                return $data['candidates'][0]['content']['parts'][0]['text'];
            }

            throw new Exception('Unexpected response format from Gemini API');

        } catch (Exception $e) {
            Log::error('GeminiChatbotService Error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Construit le prompt système avec le contexte de la base de données
     */
    private function buildSystemPrompt(array $context): string
    {
        $prompt = "Tu es un assistant chatbot intelligent pour AlumniEcho, un site de réseau social pour alumni.";
        $prompt .= "\n\nInstructions:";
        $prompt .= "\n- Réponds aux questions en français de manière courtoise et professionnelle";
        $prompt .= "\n- Sois concis et clair dans tes réponses";
        $prompt .= "\n- Si tu utilises des données, cite la source quand approprié";
        $prompt .= "\n- Si tu ne sais pas répondre, dis-le honnêtement";

        // Ajouter le contexte de la base de données
        if (!empty($context)) {
            $prompt .= "\n\nContexte de la base de données:";

            if (isset($context['promotions'])) {
                $prompt .= "\n\nPromotions disponibles: ";
                $promotions = $context['promotions'];
                if (is_array($promotions)) {
                    $promotionsList = array_map(function($p) {
                        return "{$p['nom_promotion']} (ID: {$p['code_promotion']})";
                    }, $promotions);
                    $prompt .= implode(', ', $promotionsList);
                }
            }

            if (isset($context['filieres'])) {
                $prompt .= "\n\nFilières disponibles: ";
                $filieres = $context['filieres'];
                if (is_array($filieres)) {
                    $filieresList = array_map(function($f) {
                        return "{$f['nom_filiere']} (ID: {$f['code_filiere']})";
                    }, $filieres);
                    $prompt .= implode(', ', $filieresList);
                }
            }

            if (isset($context['utilisateurs_count'])) {
                $prompt .= "\n\nNombre total d'utilisateurs: " . $context['utilisateurs_count'];
            }

            if (isset($context['souvenirs_count'])) {
                $prompt .= "\nNombre total de souvenirs: " . $context['souvenirs_count'];
            }

            if (isset($context['temoignages_count'])) {
                $prompt .= "\nNombre total de témoignages: " . $context['temoignages_count'];
            }
        }

        return $prompt;
    }

    /**
     * Teste la connexion à l'API Gemini
     */
    public function testConnection(): bool
    {
        try {
            $response = Http::timeout(10)->post(
                $this->apiUrl . '?key=' . $this->apiKey,
                [
                    'contents' => [
                        [
                            'parts' => [
                                ['text' => 'Dis bonjour']
                            ]
                        ]
                    ]
                ]
            );

            return $response->successful();
        } catch (Exception $e) {
            Log::error('Gemini Connection Test Failed: ' . $e->getMessage());
            return false;
        }
    }
}
