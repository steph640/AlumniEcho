<?php

namespace Database\Seeders;

use App\Models\Souvenir;
use App\Models\Commentaire;
use App\Models\Utilisateur;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CommentaireSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Truncate existing comments
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Commentaire::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $souvenirs = Souvenir::all();
        $utilisateurs = Utilisateur::whereIn('role_user', ['alumni', 'admin'])->get();

        $commentaires = [
            'Quel belle journée c\'était! Je me souviens que tu avais apporté ta caméra et nous avons pris plein de photos.',
            'Haha! J\'ai oublié comment c\'était! Merci de nous rappeler ces bons moments!',
            'Trop cool! Je n\'étais pas là ce jour-là, mais j\'aurais vraiment aimé y être.',
            'C\'était incroyable! On devrait refaire une réunion comme celle-ci bientôt.',
            'Merci de partager ça! Ça me rappelle pourquoi je suis heureux d\'avoir fait ces études.',
            'Haha j\'étais tellement stressé ce jour-là! Mais au final, ça valait vraiment la peine.',
            'Génial! Ça m\'a tellement manqué de se retrouver avec toute la classe.',
            'Super moment! On aurait dû enregistrer un video de cette journée.',
            'C\'était l\'une de mes meilleures expériences à l\'école. Merci de te souvenir de ça!',
            'Wow, je pensais que j\'étais le seul à me souvenir de ce détail! Tu as une bonne mémoire!',
            'C\'était fantastique! Les amis que j\'ai faits ce jour-là restent mes meilleurs amis aujourd\'hui.',
            'Magnifique! Cet événement a vraiment marqué mon parcours académique.',
            'Je suis complètement d\'accord! C\'étaient vraiment les jours où la vie était simple et belle.',
            'Très bien écrit! Tes descriptions m\'ont ramené directement à ce moment.',
            'Quelle chance d\'avoir vécu ça ensemble! Merci pour ce beau souvenir partagé.',
        ];

        foreach ($souvenirs as $souv) {
            $nbCommentaires = rand(0, 4);
            for ($i = 0; $i < $nbCommentaires; $i++) {
                $randomUser = $utilisateurs->random();
                
                Commentaire::create([
                    'code_com' => 'CM' . strtoupper(substr(uniqid(), -8)),
                    'contenu_com' => $commentaires[array_rand($commentaires)],
                    'code_user' => $randomUser->code_user,
                    'code_souv' => $souv->code_souv,
                ]);
            }
        }
    }
}
