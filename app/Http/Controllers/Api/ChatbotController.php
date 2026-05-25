<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ChatbotFaq;
use App\Models\MessageChatbot;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class ChatbotController extends Controller
{
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
     * Process user question and find matching answer
     */
    public function ask(Request $request)
    {
        try {
            $validated = $request->validate([
                'question' => 'required|string|max:500',
            ]);

            $question = $validated['question'];
            
            // Search for matching FAQ
            $faq = $this->searchFaq($question);
            
            // If no exact match found, try fuzzy search
            if (!$faq) {
                $faq = $this->fuzzySearchFaq($question);
            }
            
            // Default response if no match
            $response = $faq ? $faq->reponse_faq : $this->getDefaultResponse();
            
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
                'faq_found' => !!$faq,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Search for exact or close match in FAQs
     */
    private function searchFaq($question)
    {
        $keywords = preg_split('/\s+/', strtolower($question));
        
        // Try to find FAQ with matching keywords
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

    /**
     * Get chat history for current user
     */
    public function getHistory()
    {
        try {
            if (!Auth::check()) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            $messages = MessageChatbot::where('code_user', Auth::user()->code_user)
                ->orderBy('created_at', 'desc')
                ->limit(50)
                ->get();

            return response()->json($messages);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
