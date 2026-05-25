<?php

namespace Database\Factories;

use App\Models\Utilisateur;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Utilisateur>
 */
class UtilisateurFactory extends Factory
{
    protected $model = Utilisateur::class;
    public function definition(): array
    {
        return [
            'code_user'     => $this->faker->unique()->bothify("USER###??"),
            'nom_user'      => $this->faker->lastName(),
            'prenom_user'   => $this->faker->firstName(),
            'login_user'    => $this->faker->unique()->userName(),
            'password_user' => bcrypt('password'),
            'tel_user'      => $this->faker->unique()->phoneNumber(),
            'sexe_user'     => $this->faker->randomElement(['M','F']),
            'role_user'     => $this->faker->randomElement(['admin','alumni','visiteur']),
            'etat_user'     => $this->faker->randomElement(['actif','inactif']),
            'code_promo'    => null,
            'code_fil'      => null
        ];
    }
}
