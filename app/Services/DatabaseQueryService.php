<?php

namespace App\Services;

use App\Models\Promotion;
use App\Models\Filiere;
use App\Models\Utilisateur;
use App\Models\Souvenir;
use App\Models\Temoignage;
use Illuminate\Support\Facades\Log;

class DatabaseQueryService
{
    /**
     * Récupère le contexte de la base de données pour le chatbot
     */
    public function getContext(): array
    {
        try {
            $context = [
                'promotions' => $this->getPromotions(),
                'filieres' => $this->getFilieres(),
                'utilisateurs_count' => Utilisateur::count(),
                'souvenirs_count' => Souvenir::count(),
                'temoignages_count' => Temoignage::count(),
            ];

            return $context;
        } catch (\Exception $e) {
            Log::error('DatabaseQueryService Error: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Récupère toutes les promotions
     */
    public function getPromotions(): array
    {
        try {
            return Promotion::select('code_promotion', 'nom_promotion', 'description_promotion')
                ->get()
                ->toArray();
        } catch (\Exception $e) {
            Log::error('Error fetching promotions: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Récupère toutes les filières
     */
    public function getFilieres(): array
    {
        try {
            return Filiere::select('code_filiere', 'nom_filiere', 'description_filiere')
                ->get()
                ->toArray();
        } catch (\Exception $e) {
            Log::error('Error fetching filieres: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Récupère les utilisateurs d'une promotion spécifique
     */
    public function getUsersByPromotion(string $promotionCode): array
    {
        try {
            return Utilisateur::where('code_promotion', $promotionCode)
                ->select('code_user', 'nom_user', 'prenom_user', 'email_user', 'code_promotion')
                ->get()
                ->toArray();
        } catch (\Exception $e) {
            Log::error('Error fetching users by promotion: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Récupère les utilisateurs d'une filière spécifique
     */
    public function getUsersByFiliere(string $filiereCode): array
    {
        try {
            return Utilisateur::where('code_filiere', $filiereCode)
                ->select('code_user', 'nom_user', 'prenom_user', 'email_user', 'code_filiere')
                ->get()
                ->toArray();
        } catch (\Exception $e) {
            Log::error('Error fetching users by filiere: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Récupère les souvenirs d'une promotion
     */
    public function getSouvenirsCount(): int
    {
        try {
            return Souvenir::count();
        } catch (\Exception $e) {
            Log::error('Error fetching souvenirs count: ' . $e->getMessage());
            return 0;
        }
    }

    /**
     * Récupère les témoignages
     */
    public function getTemoignagesCount(): int
    {
        try {
            return Temoignage::count();
        } catch (\Exception $e) {
            Log::error('Error fetching temoignages count: ' . $e->getMessage());
            return 0;
        }
    }

    /**
     * Cherche des promotions par nom
     */
    public function searchPromotions(string $searchTerm): array
    {
        try {
            return Promotion::where('nom_promotion', 'like', '%' . $searchTerm . '%')
                ->select('code_promotion', 'nom_promotion', 'description_promotion')
                ->get()
                ->toArray();
        } catch (\Exception $e) {
            Log::error('Error searching promotions: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Cherche des filières par nom
     */
    public function searchFilieres(string $searchTerm): array
    {
        try {
            return Filiere::where('nom_filiere', 'like', '%' . $searchTerm . '%')
                ->select('code_filiere', 'nom_filiere', 'description_filiere')
                ->get()
                ->toArray();
        } catch (\Exception $e) {
            Log::error('Error searching filieres: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Récupère les statistiques générales
     */
    public function getStatistics(): array
    {
        try {
            return [
                'total_users' => Utilisateur::count(),
                'total_promotions' => Promotion::count(),
                'total_filieres' => Filiere::count(),
                'total_souvenirs' => Souvenir::count(),
                'total_temoignages' => Temoignage::count(),
            ];
        } catch (\Exception $e) {
            Log::error('Error fetching statistics: ' . $e->getMessage());
            return [];
        }
    }
}
