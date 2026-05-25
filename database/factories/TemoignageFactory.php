<?php

namespace Database\Factories;

use App\Models\Promotion;
use App\Models\Temoignage;
use App\Models\Utilisateur;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Temoignage>
 */
class TemoignageFactory extends Factory
{
    protected $model = Temoignage::class;
    public function definition(): array
    {
        return [
            'code_tem' => $this->faker->unique()->bothify("TEM###??"),
            'contenu_tem' => $this->faker->paragraph(),
            'valide_tem' => $this->faker->boolean(),
            'code_user' => Utilisateur::factory(),
            'code_promo' => Promotion::factory()
        ];
    }
}
