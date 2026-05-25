<?php

namespace Database\Factories;

use App\Models\Promotion;
use App\Models\Souvenir;
use App\Models\Utilisateur;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Souvenir>
 */
class SouvenirFactory extends Factory
{
    protected $model = Souvenir::class;
    public function definition(): array
    {
        return [
            'code_souv' => $this->faker->unique()->bothify("SOUV###??"),
            'titre_souv' => $this->faker->sentence(3),
            'description_souv' => $this->faker->paragraph,
            'url_photo_souv' => $this->faker->imageUrl(640, 480, 'souvenir', true),
            'code_user' => Utilisateur::factory(),
            'code_promo' => Promotion::factory()
        ];
    }
}
