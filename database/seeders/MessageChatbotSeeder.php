<?php

namespace Database\Seeders;

use App\Models\MessageChatbot;
use App\Models\Utilisateur;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MessageChatbotSeeder extends Seeder
{

    public function run(): void
    {
        $utilisateurs = Utilisateur::all();

        foreach ($utilisateurs as $user) {
            MessageChatbot::factory(2)->create([
                'code_user' => $user->code_user,
            ]);
        }
    }
}
