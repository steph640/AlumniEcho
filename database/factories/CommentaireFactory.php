<?php

namespace Database\Factories;

use App\Models\Commentaire;
use App\Models\Souvenir;
use App\Models\Utilisateur;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Commentaire>
 */
class CommentaireFactory extends Factory
{
    public function definition(): array
    {
        return [
            'code_com' => $this->faker->unique()->bothify("COM####??"),
            'contenu_com' => $this->faker->sentence,
            'code_user' => Utilisateur::factory(),
            'code_souv' => Souvenir::factory(),
        ];
    }
}
