<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ChatbotFaq;
use App\Models\MessageChatbot;
use App\Services\GeminiChatbotService;
use App\Services\DatabaseQueryService;
use App\Services\QuestionAnalysisService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Exception;

class ChatbotController extends Controller
{
    private GeminiChatbotService $geminiService;
    private DatabaseQueryService $databaseService;
    private QuestionAnalysisService $analysisService;

    public function __construct(
        GeminiChatbotService $geminiService,
        DatabaseQueryService $databaseService,
        QuestionAnalysisService $analysisService
    ) {
        $this->geminiService = $geminiService;
        $this->databaseService = $databaseService;
        $this->analysisService = $analysisService;
    }

    /**
     * Get all FAQs
     */
    public function getFaqs()
    {
        try {
            $faqs = ChatbotFaq::all(['code_faq', 'question_faq', 'reponse_faq']);
            return response()->json($faqs);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Process user question with intelligent AI response
     */
    public function ask(Request $request)
    {
        try {
            $validated = $request->validate([
                'question' => 'required|string|max:1000',
                'use_ai' => 'sometimes|boolean',
            ]);

            $question = $validated['question'];
            $useAI = $validated['use_ai'] ?? true; // Use AI by default

            $response = null;
            $faqFound = false;
            $aiGenerated = false;

            if ($useAI) {
                try {
                    // Analyser la question
                    $analysis = $this->analysisService->analyzeQuestion($question);
                    Log::info('Question Analysis: ', $analysis);

                    // Récupérer le contexte de la BD
                    $baseContext = $this->databaseService->getContext();

                    // Enrichir le contexte basé sur l'analyse
                    $enrichedContext = $this->analysisService->enrichContext(
                        $baseContext,
                        $analysis,
                        $this->databaseService
                    );
                    Log::info('Enriched Context Keys: ', array_keys($enrichedContext));

                    // Générer une réponse avec Gemini
                    $response = $this->geminiService->generateResponse($question, $enrichedContext);
                    $aiGenerated = true;

                } catch (Exception $e) {
                    Log::warning('AI generation failed, falling back to FAQ search: ' . $e->getMessage());

                    // Fallback: search FAQ if AI fails
                    $faq = $this->searchFaq($question);
                    if (!$faq) {
                        $faq = $this->fuzzySearchFaq($question);
                    }

                    $response = $faq ? $faq->reponse_faq : $this->getDefaultResponse();
                    $faqFound = !!$faq;
                }
            } else {
                // Use traditional FAQ search
                $faq = $this->searchFaq($question);
                if (!$faq) {
                    $faq = $this->fuzzySearchFaq($question);
                }

                $response = $faq ? $faq->reponse_faq : $this->getDefaultResponse();
                $faqFound = !!$faq;
            }

            // Save message to history if user is authenticated
            $messageData = [
                'code_message' => 'MSG' . strtoupper(substr(uniqid(), -12)),
                'question_chatbot' => $question,
                'reponse_chatbot' => $response,
            ];

            if (Auth::check()) {
                $messageData['code_user'] = Auth::user()->code_user;
                MessageChatbot::create($messageData);
            }

            return response()->json([
                'reponse' => $response,
                'code_message' => $messageData['code_message'],
                'faq_found' => $faqFound,
                'ai_generated' => $aiGenerated,
            ]);

        } catch (\Exception $e) {
            Log::error('ChatbotController Error: ' . $e->getMessage());
            return response()->json(['error' => 'Une erreur s\'est produite lors du traitement de votre question.'], 500);
        }
    }

    /**
     * Get chat history for authenticated user
     */
    public function getHistory(Request $request)
    {
        try {
            $user = Auth::user();

            $messages = MessageChatbot::where('code_user', $user->code_user)
                ->orderByDesc('created_at')
                ->limit(50)
                ->get(['code_message', 'question_chatbot', 'reponse_chatbot', 'created_at']);

            return response()->json($messages);

        } catch (\Exception $e) {
            Log::error('ChatbotController getHistory Error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Search for exact or close match in FAQs
     */
    private function searchFaq($question)
    {
        $keywords = preg_split('/\s+/', strtolower($question));
        $faqs = ChatbotFaq::all();

        foreach ($faqs as $faq) {
            $faqKeywords = preg_split('/\s+/', strtolower($faq->question_faq));
            $matchCount = count(array_intersect($keywords, $faqKeywords));

            // If at least 50% of keywords match
            if ($matchCount >= count($keywords) * 0.5) {
                return $faq;
            }
        }

        return null;
    }

    /**
     * Fuzzy search using Levenshtein distance
     */
    private function fuzzySearchFaq($question)
    {
        $faqs = ChatbotFaq::all();
        $bestMatch = null;
        $bestScore = 0.6; // 60% similarity threshold

        foreach ($faqs as $faq) {
            // Calculate similarity
            $similarity = $this->calculateSimilarity(
                strtolower($question),
                strtolower($faq->question_faq)
            );

            if ($similarity > $bestScore) {
                $bestScore = $similarity;
                $bestMatch = $faq;
            }
        }

        return $bestMatch;
    }

    /**
     * Calculate string similarity (0 to 1)
     */
    private function calculateSimilarity($str1, $str2)
    {
        $len = max(strlen($str1), strlen($str2));
        if ($len == 0) return 1.0;

        $distance = levenshtein($str1, $str2);
        return 1 - ($distance / $len);
    }

    /**
     * Get default response when no FAQ matches
     */
    private function getDefaultResponse()
    {
        $responses = [
            'Je n\'ai pas trouvé de réponse précise à votre question. Pouvez-vous reformuler ou essayer d\'autres mots-clés?',
            'Désolé, je ne suis pas sûr de comprendre votre question. Pourriez-vous être plus spécifique?',
            'Je n\'ai pas trouvé d\'information sur ce sujet. Consultez nos FAQs ou contactez l\'équipe d\'AlumniEcho.',
            'Votre question dépasse mon champ de connaissance. N\'hésitez pas à nous contacter directement.',
        ];

        return $responses[array_rand($responses)];
    }
}
