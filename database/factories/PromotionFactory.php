<?php

namespace Database\Factories;

use App\Models\Promotion;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Promotion>
 */
class PromotionFactory extends Factory
{
    protected $model = Promotion::class;
    public function definition(): array
    {
        $annee = $this->faker->dateTimeBetween('2000-01-01','now')->format('Y');

        return [
            'code_promo' => $this->faker->unique()->bothify("PROMO???##"),
            'nom_promo' => 'Promo' . $this->faker->bothify('???') . $annee,
            'annee_promo' => $annee,
            'code_fil' => null,
        ];
    }
}
