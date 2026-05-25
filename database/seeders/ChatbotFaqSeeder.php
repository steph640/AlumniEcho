<?php

namespace Database\Seeders;

use App\Models\ChatbotFaq;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ChatbotFaqSeeder extends Seeder
{
    public function run(): void
    {
        // Truncate existing FAQs
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        ChatbotFaq::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $faqs = [
            [
                'question' => 'Qu\'est-ce que AlumniEcho?',
                'reponse' => 'AlumniEcho est une plateforme sociale dédiée aux anciens étudiants (alumni) pour rester connectés, partager leurs expériences, souvenirs et témoignages, et maintenir une communauté forte après leurs études.',
            ],
            [
                'question' => 'Comment créer un compte sur AlumniEcho?',
                'reponse' => 'Cliquez sur le bouton "S\'inscrire" sur la page d\'accueil, remplissez le formulaire d\'inscription avec vos informations personnelles (nom, prénom, login, mot de passe), sélectionnez votre filière et promotion, puis validez. Vous pourrez alors vous connecter immédiatement.',
            ],
            [
                'question' => 'Comment me connecter à mon compte?',
                'reponse' => 'Allez sur la page de connexion, entrez votre identifiant (login) et votre mot de passe, puis cliquez sur "Se connecter". Vous accèderez à votre tableau de bord personnel.',
            ],
            [
                'question' => 'Qu\'est-ce qu\'un souvenir?',
                'reponse' => 'Un souvenir est une contribution personnelle où vous pouvez partager vos moments mémorables lors de vos études: événements, anecdotes, photos, moments marquants avec d\'autres étudiants, etc.',
            ],
            [
                'question' => 'Comment ajouter un souvenir?',
                'reponse' => 'Dans votre tableau de bord alumni, cliquez sur "+ Ajouter un Souvenir", remplissez le titre et la description de votre souvenir, puis cliquez sur "Soumettre". Votre souvenir sera visible à tous les autres alumni.',
            ],
            [
                'question' => 'Comment modifier ou supprimer mon souvenir?',
                'reponse' => 'Allez dans la section "Mes Souvenirs", trouvez le souvenir que vous voulez modifier, cliquez sur "Modifier" pour le changer ou "Supprimer" pour le retirer. Seuls vos propres souvenirs peuvent être modifiés ou supprimés.',
            ],
            [
                'question' => 'Qu\'est-ce qu\'un témoignage?',
                'reponse' => 'Un témoignage est un partage d\'expérience plus personnel où vous racontez votre parcours après vos études, vos projets professionnels, vos réalisations, vos conseils pour les générations futures, etc.',
            ],
            [
                'question' => 'Comment partager un témoignage?',
                'reponse' => 'Accédez à "Mes Témoignages" depuis votre tableau de bord, cliquez sur "+ Ajouter un Témoignage", rédigez votre histoire ou vos conseils, puis validez pour le publier.',
            ],
            [
                'question' => 'Comment commenter un souvenir ou un témoignage?',
                'reponse' => 'En consultant un souvenir ou un témoignage, vous verrez une section commentaires en bas de la page. Cliquez sur le champ de commentaire, écrivez votre message, et validez-le pour le publier.',
            ],
            [
                'question' => 'Comment consulter tous les souvenirs?',
                'reponse' => 'Depuis votre profil alumni, accédez à la section "Mes Souvenirs" pour voir vos contributions, ou naviguez vers "Souvenirs" pour explorer tous les souvenirs partagés par la communauté.',
            ],
            [
                'question' => 'Y a-t-il un système de roles sur AlumniEcho?',
                'reponse' => 'Oui, il existe trois rôles: Admin (gestion complète), Alumni (accès complet aux fonctionnalités), et Visiteur (consultation uniquement, sans possibilité de publier).',
            ],
            [
                'question' => 'Qu\'est-ce qu\'un administrateur peut faire?',
                'reponse' => 'Les administrateurs gèrent la plateforme: modération des contenus, gestion des utilisateurs, création de FAQs pour le chatbot, gestion des filières et promotions, et supervision générale de la communauté.',
            ],
            [
                'question' => 'Qu\'est-ce qu\'un visiteur peut faire?',
                'reponse' => 'Les visiteurs peuvent consulter tous les souvenirs, témoignages et commentaires, mais ne peuvent pas créer de contenu ni commenter. Ils doivent se créer un compte alumni pour participer activement.',
            ],
            [
                'question' => 'Comment modifier mon profil?',
                'reponse' => 'Cliquez sur "Mon Profil" dans le menu de navigation, modifiez vos informations personnelles (nom, prénom, téléphone, sexe, filière, promotion), puis cliquez sur "Mettre à jour".',
            ],
            [
                'question' => 'Peut-on modifier son mot de passe?',
                'reponse' => 'Actuellement, la modification de mot de passe doit être contactée via l\'équipe d\'administration. Vous pouvez nous envoyer une demande de réinitialisation de mot de passe.',
            ],
            [
                'question' => 'Comment rechercher un alumnus spécifique?',
                'reponse' => 'Vous pouvez consulter la liste des utilisateurs et filtrer par filière ou promotion pour trouver d\'autres alumni de votre année ou domaine d\'études.',
            ],
            [
                'question' => 'Comment contacter directement un autre alumnus?',
                'reponse' => 'Vous pouvez voir le numéro de téléphone des alumni dans leurs profils publics. Pour les contacts confidentiels, envoyez un message via la plateforme ou consultez l\'administrateur.',
            ],
            [
                'question' => 'Quelles sont les règles de modération?',
                'reponse' => 'Le contenu doit être respectueux, pertinent et approprié. Les contenus offensants, hors sujet, ou contenant du spam seront supprimés. Les utilisateurs qui violent les règles peuvent être suspendus.',
            ],
            [
                'question' => 'Mes données personnelles sont-elles sécurisées?',
                'reponse' => 'Oui, AlumniEcho utilise des mesures de sécurité standards pour protéger vos données. Seuls les informations que vous rendez publiques sont visibles aux autres utilisateurs.',
            ],
            [
                'question' => 'Comment réinitialiser mon mot de passe?',
                'reponse' => 'Sur la page de connexion, cliquez sur "Mot de passe oublié", entrez votre email ou login, et suivez les instructions pour réinitialiser votre mot de passe via un lien d\'activation.',
            ],
            [
                'question' => 'Comment mettre à jour ma filière ou promotion?',
                'reponse' => 'Dans votre profil, vous pouvez sélectionner votre filière et votre promotion dans les menus déroulants. Cliquez sur "Mettre à jour" pour enregistrer les changements.',
            ],
            [
                'question' => 'Peut-on supprimer mon compte?',
                'reponse' => 'Pour demander la suppression de votre compte, contactez l\'équipe d\'administration. Veuillez noter que certains contenus que vous avez publié resteront visibles selon les règles de la plateforme.',
            ],
            [
                'question' => 'Combien de souvenirs ou témoignages puis-je créer?',
                'reponse' => 'Il n\'y a pas de limite au nombre de souvenirs ou de témoignages que vous pouvez créer. Partagez autant de contributions que vous le souhaitez!',
            ],
            [
                'question' => 'Puis-je éditer un commentaire que j\'ai posté?',
                'reponse' => 'Actuellement, les commentaires ne peuvent pas être édités une fois publiés. Si vous avez fait une erreur, vous pouvez le supprimer et publier un nouveau commentaire.',
            ],
            [
                'question' => 'Comment utiliser le ChatBot AlumniEcho?',
                'reponse' => 'Le ChatBot est disponible pour tous les utilisateurs connectés. Posez vos questions en langage naturel et recevez des réponses instant. Vous pouvez aussi consulter les questions fréquentes pour trouver des réponses rapides.',
            ],
            [
                'question' => 'Le ChatBot peut-il répondre à toutes mes questions?',
                'reponse' => 'Le ChatBot répond aux questions fréquemment posées. Si votre question n\'est pas couverte, il vous suggérera de contacter directement l\'équipe d\'administration pour une assistance personnalisée.',
            ],
            [
                'question' => 'Est-ce que mon historique de chat est sauvegardé?',
                'reponse' => 'Oui, vos conversations avec le ChatBot sont enregistrées pour améliorer la qualité du service et vous permettre de consulter votre historique.',
            ],
        ];

        foreach ($faqs as $faq) {
            ChatbotFaq::create([
                'code_faq' => 'FAQ' . strtoupper(substr(uniqid(), -7)),
                'question_faq' => $faq['question'],
                'reponse_faq' => $faq['reponse'],
            ]);
        }
    }
}
