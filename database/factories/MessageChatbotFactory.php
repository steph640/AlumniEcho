<?php

namespace Database\Factories;

use App\Models\MessageChatbot;
use App\Models\Utilisateur;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<MessageChatbot>
 */
class MessageChatbotFactory extends Factory
{
    protected $model = MessageChatbot::class;
    public function definition(): array
    {
        return [
            'code_message' => $this->faker->unique()->bothify("MSG####??"),
            'code_user' => Utilisateur::factory(),
            'question_chatbot' => $this->faker->sentence,
            'reponse_chatbot' => $this->faker->optional()->paragraph,
        ];
    }
}
