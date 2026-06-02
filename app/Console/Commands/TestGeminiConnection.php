<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\GeminiChatbotService;
use Exception;

class TestGeminiConnection extends Command
{
    protected $signature = 'gemini:test';
    protected $description = 'Test la connexion à l\'API Google Gemini';

    public function handle()
    {
        try {
            $this->info('🔄 Test de connexion à Google Gemini API...');

            $geminiService = new GeminiChatbotService();

            if ($geminiService->testConnection()) {
                $this->info('✅ Connexion réussie à Google Gemini API!');

                // Essayer une réponse simple
                $this->info('🤖 Test avec une question simple...');
                $response = $geminiService->generateResponse('Bonjour, comment ça marche AlumniEcho?', [
                    'utilisateurs_count' => 0,
                    'souvenirs_count' => 0,
                    'temoignages_count' => 0,
                ]);

                $this->info('✅ Réponse de Gemini:');
                $this->line($response);

                return Command::SUCCESS;
            } else {
                $this->error('❌ Impossible de se connecter à Google Gemini API');
                return Command::FAILURE;
            }

        } catch (Exception $e) {
            $this->error('❌ Erreur: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
