<?php

namespace Database\Seeders;

use App\Models\Souvenir;
use App\Models\Utilisateur;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SouvenirSeeder extends Seeder
{
    public function run(): void
    {
        // Désactiver les contraintes FK pour le truncate
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Souvenir::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $utilisateurs = Utilisateur::whereIn('role_user', ['alumni', 'admin'])->get();

        $souvenirs = [
            ['titre' => 'Remise des diplômes 2024', 'description' => "Un moment inoubliable avec toute la promotion. L'excitation, l'émotion et la fierté de voir nos efforts récompensés."],
            ['titre' => 'Hackathon 24h', 'description' => "Notre équipe a développé une application IoT en une seule nuit. L'ambiance était électrique, le café coulait à flots!"],
            ['titre' => 'Sortie promotion au lac', 'description' => "Une journée mémorable avec toute la classe. Baignade, jeux, barbecue et rires non-stop. De vraies amitiés sont nées ce jour-là."],
            ['titre' => "Projet fin d'études", 'description' => "Nous avons développé une application mobile complète de gestion de tâches collaboratives. Un vrai défi relevé avec succès."],
            ['titre' => 'Conférence avec un CTO alumni', 'description' => "Un ancien étudiant devenu Chief Technology Officer d'une startup est venu partager son parcours. Très inspirant pour toute la promo."],
            ['titre' => 'Championnat de foot inter-promo', 'description' => "Notre promotion a remporté le championnat! L'ambiance dans le stade était folle. Quelle célébration après la victoire!"],
            ['titre' => "Voyage d'études à Berlin", 'description' => "Une semaine pour participer à la plus grande conférence tech d'Europe. IA, cloud computing, cybersécurité: que de découvertes!"],
            ['titre' => 'Présentation data science', 'description' => "Notre groupe a présenté un projet d'analyse prédictive en machine learning. Le jury a été très impressionné par nos résultats."],
            ['titre' => "Soirée d'intégration", 'description' => "Nous avons organisé une soirée pour accueillir les nouvelles promotions. Jeux, musique et animations pour créer une belle communauté."],
            ['titre' => 'Contribution open source', 'description' => "Notre classe a contribué à plusieurs projets open source majeurs. Savoir que notre code aide des développeurs partout dans le monde est gratifiant."],
        ];

        $souvenirIndex = 0;
        foreach ($utilisateurs as $user) {
            $nbSouvenirs = rand(1, 3);
            for ($i = 0; $i < $nbSouvenirs; $i++) {
                $souvenir = $souvenirs[$souvenirIndex % count($souvenirs)];
                Souvenir::create([
                    'code_souv'        => 'SV' . strtoupper(substr(uniqid(), -8)),
                    'titre_souv'       => $souvenir['titre'],
                    'description_souv' => $souvenir['description'],
                    'url_photo_souv'   => null,
                    'code_user'        => $user->code_user,
                    'code_promo'       => $user->code_promo,
                ]);
                $souvenirIndex++;
            }
        }
    }
}
