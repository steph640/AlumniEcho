<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;
    public function run(): void
    {
        $this->call([
            FiliereSeeder::class,
            PromotionSeeder::class,
            UtilisateurSeeder::class,
            SouvenirSeeder::class,
            TemoignageSeeder::class,
            CommentaireSeeder::class,
            ChatbotFaqSeeder::class,
            MessageChatbotSeeder::class,
        ]);
    }
}
