<?php

namespace Database\Factories;

use App\Models\Filiere;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Filiere>
 */
class FiliereFactory extends Factory
{
    protected $model = Filiere::class;
    public function definition(): array
    {
        $nom = ucfirst($this->faker->word);
        return [
            'code_fil' => $this->faker->unique()->bothify("FIL####??"),
            'nom_fil' => $nom,
            'description_fil' => 'Filiere ' . $nom . $this->faker->sentence
        ];
    }
}
