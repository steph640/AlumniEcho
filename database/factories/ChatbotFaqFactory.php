<?php

namespace Database\Factories;

use App\Models\ChatbotFaq;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ChatbotFaq>
 */
class ChatbotFaqFactory extends Factory
{

    public function definition(): array
    {
        return [
            'code_faq' => $this->faker->unique()->bothify("FAQ####??"),
            'question_faq' => $this->faker->sentence,
            'reponse_faq' => $this->faker->paragraph
        ];
    }
}
