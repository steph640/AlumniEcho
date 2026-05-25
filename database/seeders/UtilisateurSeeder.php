<?php

namespace Database\Seeders;

use App\Models\Promotion;
use App\Models\Utilisateur;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UtilisateurSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $promotions = Promotion::all();
        $firstPromo = $promotions->first();
        $firstFil = $firstPromo ? $firstPromo->code_fil : null;
        
        // Create test users with known credentials
        if ($firstPromo) {
            // Admin user
            Utilisateur::create([
                'code_user' => 'USER' . strtoupper(substr(uniqid(), -6)),
                'login_user' => 'admin',
                'password_user' => \Illuminate\Support\Facades\Hash::make('admin123'),
                'nom_user' => 'Administrator',
                'prenom_user' => 'Admin',
                'tel_user' => '0000000000',
                'sexe_user' => 'M',
                'role_user' => 'admin',
                'etat_user' => 'actif',
                'code_promo' => $firstPromo->code_promo,
                'code_fil' => $firstFil,
            ]);
            
            // Alumni user
            Utilisateur::create([
                'code_user' => 'USER' . strtoupper(substr(uniqid(), -6)),
                'login_user' => 'alumni',
                'password_user' => \Illuminate\Support\Facades\Hash::make('alumni123'),
                'nom_user' => 'Alumni',
                'prenom_user' => 'Test',
                'tel_user' => '0111111111',
                'sexe_user' => 'F',
                'role_user' => 'alumni',
                'etat_user' => 'actif',
                'code_promo' => $firstPromo->code_promo,
                'code_fil' => $firstFil,
            ]);
            
            // Visiteur user
            Utilisateur::create([
                'code_user' => 'USER' . strtoupper(substr(uniqid(), -6)),
                'login_user' => 'visiteur',
                'password_user' => \Illuminate\Support\Facades\Hash::make('visiteur123'),
                'nom_user' => 'Visiteur',
                'prenom_user' => 'Test',
                'tel_user' => '0222222222',
                'sexe_user' => 'M',
                'role_user' => 'visiteur',
                'etat_user' => 'actif',
                'code_promo' => $firstPromo->code_promo,
                'code_fil' => $firstFil,
            ]);
        }

        // Create factory-generated users
        foreach ($promotions as $promo) {
            Utilisateur::factory(5)->create([
                'code_promo' => $promo->code_promo,
                'code_fil'   => $promo->code_fil,
            ]);
        }
    }
}
