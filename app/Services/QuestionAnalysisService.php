<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class QuestionAnalysisService
{
    /**
     * Analyse une question pour extraire les intentions
     */
    public function analyzeQuestion(string $question): array
    {
        $question_lower = strtolower($question);

        $analysis = [
            'question' => $question,
            'query_type' => null,
            'promotion_keywords' => [],
            'filiere_keywords' => [],
            'user_keywords' => [],
            'action' => null,
        ];

        // Déterminer le type de requête
        if ($this->containsKeywords($question_lower, ['promo', 'promotion', 'classe', 'année'])) {
            $analysis['query_type'] = 'promotion';
            $analysis['promotion_keywords'] = $this->extractTerms($question_lower, ['2023', '2024', '2025', '2026', '2022', '2021', '2020', '2019']);
        }

        if ($this->containsKeywords($question_lower, ['filière', 'filiere', 'specialite', 'spécialité', 'branche'])) {
            $analysis['query_type'] = 'filiere';
            $analysis['filiere_keywords'] = $this->extractTerms($question_lower, []);
        }

        if ($this->containsKeywords($question_lower, ['élève', 'eleve', 'étudiant', 'etudiant', 'personne', 'qui', 'quels', 'quelles'])) {
            $analysis['action'] = 'list_users';
        }

        if ($this->containsKeywords($question_lower, ['nombre', 'combien', 'total', 'count'])) {
            $analysis['action'] = 'count';
        }

        if ($this->containsKeywords($question_lower, ['info', 'information', 'détail', 'detail', 'description'])) {
            $analysis['action'] = 'get_info';
        }

        if ($this->containsKeywords($question_lower, ['souvenir', 'témoignage', 'temoignage'])) {
            $analysis['query_type'] = 'memories';
        }

        return $analysis;
    }

    /**
     * Vérifie si la question contient certains mots-clés
     */
    private function containsKeywords(string $text, array $keywords): bool
    {
        foreach ($keywords as $keyword) {
            if (strpos($text, $keyword) !== false) {
                return true;
            }
        }
        return false;
    }

    /**
     * Extrait les termes pertinents du texte
     */
    private function extractTerms(string $text, array $knownTerms): array
    {
        $words = preg_split('/\W+/', $text, -1, PREG_SPLIT_NO_EMPTY);
        $relevantTerms = [];

        if (!empty($knownTerms)) {
            $relevantTerms = array_intersect($words, $knownTerms);
        }

        return array_unique($relevantTerms);
    }

    /**
     * Enrichit le contexte basé sur l'analyse de la question
     */
    public function enrichContext(array $baseContext, array $analysis, DatabaseQueryService $queryService): array
    {
        try {
            $enrichedContext = $baseContext;

            // Si c'est une question sur une promotion spécifique
            if ($analysis['query_type'] === 'promotion' && !empty($analysis['promotion_keywords'])) {
                $keyword = array_values($analysis['promotion_keywords'])[0];
                $promotions = $queryService->searchPromotions($keyword);

                if (!empty($promotions)) {
                    $promotion = $promotions[0];
                    $enrichedContext['specific_promotion'] = $promotion;

                    // Si l'action est de lister les utilisateurs
                    if ($analysis['action'] === 'list_users') {
                        $users = $queryService->getUsersByPromotion($promotion['code_promotion']);
                        $enrichedContext['related_users'] = $users;
                        $enrichedContext['users_count'] = count($users);
                    }
                }
            }

            // Si c'est une question sur une filière spécifique
            if ($analysis['query_type'] === 'filiere') {
                $filieres = $queryService->getFilieres();

                if (!empty($filieres)) {
                    $enrichedContext['all_filieres'] = $filieres;

                    // Si l'action est de lister les utilisateurs
                    if ($analysis['action'] === 'list_users' && !empty($filieres)) {
                        $firstFiliere = $filieres[0];
                        $users = $queryService->getUsersByFiliere($firstFiliere['code_filiere']);
                        $enrichedContext['related_users'] = $users;
                        $enrichedContext['users_count'] = count($users);
                    }
                }
            }

            // Si c'est une question sur les statistiques
            if ($analysis['action'] === 'count') {
                $enrichedContext['statistics'] = $queryService->getStatistics();
            }

            return $enrichedContext;

        } catch (\Exception $e) {
            Log::error('QuestionAnalysisService Error: ' . $e->getMessage());
            return $baseContext;
        }
    }

    /**
     * Crée un prompt enrichi pour Gemini basé sur l'analyse
     */
    public function buildEnrichedPrompt(array $analysis, array $context): string
    {
        $prompt = "Tu es un assistant chatbot pour AlumniEcho.";
        $prompt .= "\n\nRéponds à la question suivante en français, de manière courtoise et précise.";

        // Ajouter des instructions spécifiques basées sur l'analyse
        if ($analysis['query_type'] === 'promotion' && isset($context['specific_promotion'])) {
            $promotion = $context['specific_promotion'];
            $prompt .= "\n\nContexte: L'utilisateur pose une question sur la promotion '{$promotion['nom_promotion']}'.";

            if (isset($context['related_users'])) {
                $prompt .= "\nNombre d'élèves dans cette promotion: " . $context['users_count'];
                $prompt .= "\nPremiers élèves: ";

                $topUsers = array_slice($context['related_users'], 0, 5);
                $usersList = array_map(function($u) {
                    return "{$u['prenom_user']} {$u['nom_user']}";
                }, $topUsers);
                $prompt .= implode(", ", $usersList);

                if ($context['users_count'] > 5) {
                    $prompt .= "... et " . ($context['users_count'] - 5) . " autres";
                }
            }
        }

        if ($analysis['action'] === 'count' && isset($context['statistics'])) {
            $stats = $context['statistics'];
            $prompt .= "\n\nStatistiques du site:";
            $prompt .= "\n- Total utilisateurs: " . $stats['total_users'];
            $prompt .= "\n- Total promotions: " . $stats['total_promotions'];
            $prompt .= "\n- Total filières: " . $stats['total_filieres'];
            $prompt .= "\n- Total souvenirs: " . $stats['total_souvenirs'];
            $prompt .= "\n- Total témoignages: " . $stats['total_temoignages'];
        }

        return $prompt;
    }
}
