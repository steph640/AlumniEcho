<?php

namespace Database\Seeders;

use App\Models\Temoignage;
use App\Models\Utilisateur;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TemoignageSeeder extends Seeder
{
    public function run(): void
    {
        // Truncate existing temoignages
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Temoignage::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $utilisateurs = Utilisateur::whereIn('role_user', ['alumni', 'admin'])->get();

        $temoignages = [
            'Après mes études, j\'ai tout d\'abord travaillé comme développeur junior dans une PME. Cette expérience m\'a permis d\'apprendre la rigueur et les bonnes pratiques de développement. Trois ans plus tard, j\'ai décidé de fonder ma propre startup avec deux anciens camarades. Aujourd\'hui, nous avons levé 500k€ et employons 15 personnes. Mon conseil: n\'ayez pas peur de prendre des risques calculés!',

            'J\'ai choisi de travailler en tant que Freelancer après mes études. C\'est une belle liberté, mais cela demande de la discipline et de bonnes capacités en gestion de projet. En 5 ans, j\'ai travaillé avec des clients de plus de 20 pays différents. Je gagne bien ma vie, mais le plus gratifiant est de construire des produits qui font vraiment la différence.',

            'Directement après la graduation, j\'ai intégré Google en tant qu\'ingénieur logiciel. L\'expérience dans une grande tech company m\'a permis de comprendre comment fonctionnent les systèmes distribués à grande échelle. C\'est une belle expérience, mais j\'envisage maintenant de revenir dans une startup plus petite pour avoir plus d\'impact direct.',

            'Ma carrière a pris une tournure inattendue: je suis passée du développement au management. Aujourd\'hui, je suis leader technique dans une équipe de 8 personnes. Le secret du succès? Écouter ses collaborateurs, apprendre constamment et ne jamais arrêter de coder un peu.',

            'Le secteur du e-commerce m\'a beaucoup intéressée. J\'ai commencé comme développeuse backend et j\'ai progressé jusqu\'à devenir architecte solution. Les défis de scalabilité m\'ont passionnée. Mon expérience dans AlumniEcho avec les architectures en microservices m\'a vraiment préparée à ces défis.',

            'J\'ai choisi la voie du DevOps et de l\'infrastructure cloud. Cette spécialisation n\'était pas très populaire à l\'époque de mes études, mais c\'était une excellente décision. Cloud computing est maintenant partout et je suis très demandé sur le marché. Conseil: identifiez les tendances émergentes!',

            'Après mes études, j\'ai travaillé pendant 3 ans en tant que développeur, puis j\'ai décidé de me reconvertir en Product Manager. C\'est un changement majeur, mais mon background technique m\'a donné un avantage compétitif. Aujourd\'hui, j\'aime mieux mon travail que jamais.',

            'Je suis restée en contact avec deux camarades de classe et nous avons fondé une agence web ensemble. Nous avons démarré en travaillant de notre garage avec très peu de capital. 7 ans plus tard, nous avons 5 employés permanents et nous travaillons pour des clients de toute l\'Afrique. L\'aventure entrepreneuriale est incroyable!',

            'Le secteur de la cybersécurité m\'a fasciné depuis les cours de sécurité système à l\'école. J\'ai suivi une formation complémentaire et je suis maintenant expert en sécurité cloud. C\'est un secteur en forte croissance et très bien rémunéré. Je recommande fortement à ceux qui aiment les défis!',

            'Après avoir obtenu mon diplôme, j\'ai continué mes études pour faire un Master en Intelligence Artificielle. Cette décision m\'a permis de travailler dans des projets fascinants utilisant le deep learning et le NLP. Je suis actuellement chercheur dans un laboratoire de recherche et j\'adore contribuer à l\'avancement de la science!',
        ];

        $temoignageIndex = 0;
        foreach ($utilisateurs as $user) {
            $nbTemoignages = rand(0, 2);
            for ($i = 0; $i < $nbTemoignages; $i++) {
                $temoignage = $temoignages[$temoignageIndex % count($temoignages)];
                
                Temoignage::create([
                    'code_tem' => 'TM' . strtoupper(substr(uniqid(), -8)),
                    'contenu_tem' => $temoignage,
                    'valide_tem' => true,
                    'code_user' => $user->code_user,
                    'code_promo' => $user->code_promo,
                ]);
                
                $temoignageIndex++;
            }
        }
    }
}
