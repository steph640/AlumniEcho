<?php

use App\Http\Controllers\web\CommentaireController;
use App\Http\Controllers\web\FiliereController;
use App\Http\Controllers\web\SouvenirController;
use App\Http\Controllers\web\TemoignageController;
use App\Http\Controllers\web\UtilisateurController;
use App\Http\Controllers\web\PromotionController;
use App\Http\Controllers\web\ChatbotFaqController;
use App\Http\Controllers\web\ChatbotController;
use App\Http\Controllers\web\MessageChatbotController;
use App\Http\Controllers\web\AdminController;
use App\Http\Controllers\web\AlumniController;
use App\Http\Controllers\web\VisiteurController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\File\Stream;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;

//Rendre les fichiers de stockage accessible publiquement
Route::get('/files/{path}', function ($path) {
    $fullPath = public_path('storage') . '/' . $path;
    
    if (!is_file($fullPath)) {
        return abort(404);
    }
    
    return response()->file($fullPath);
})->where('path', '.*')->name('files.serve');


//template alternatifs
Route::get('/template1', function () {
    return view('welcome')->with('template', 'template1');
});
Route::get('/template2', function () {
    return view('welcome')->with('template', 'template2');
});
Route::get('/template3', function () {
    return view('welcome')->with('template', 'template3');
});
Route::get('/template4', function () {
    return view('welcome')->with('template', 'template4');
});
Route::get('/template5', function () {
    return view('welcome')->with('template', 'template5');
});

//Accueil
Route::get('/', [WelcomeController::class, 'index']);

// Temporary public route for chatbot testing
Route::get('/chatbot-test', [ChatbotController::class, 'index'])->name('chatbot.test');

// Routes d'authentification
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'loginWeb'])->name('login.post');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'registerWeb'])->name('register.post');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// Routes Admin
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::resource('utilisateurs', UtilisateurController::class);
    Route::resource('souvenirs', SouvenirController::class);
    Route::resource('temoignages', TemoignageController::class);
    Route::resource('commentaires', CommentaireController::class);
    Route::resource('filieres', FiliereController::class);
    Route::resource('promotions', PromotionController::class);
    Route::get('/chatbot', [ChatbotController::class, 'index'])->name('chatbot');
});

// Routes Alumni
Route::middleware(['auth', 'role:alumni'])->prefix('alumni')->name('alumni.')->group(function () {
    Route::get('/dashboard', [AlumniController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile', [AlumniController::class, 'profile'])->name('profile');
    Route::put('/profile', [AlumniController::class, 'updateProfile'])->name('profile.update');
    Route::resource('souvenirs', SouvenirController::class);
    Route::resource('temoignages', TemoignageController::class);
    Route::resource('commentaires', CommentaireController::class);
    Route::get('/chatbot', [ChatbotController::class, 'index'])->name('chatbot');
});

// Routes Visiteur (Consultation seule)
Route::middleware(['auth', 'role:visiteur'])->prefix('visiteur')->name('visiteur.')->group(function () {
    Route::get('/dashboard', [VisiteurController::class, 'dashboard'])->name('dashboard');
    Route::get('/souvenirs', [SouvenirController::class, 'index'])->name('souvenirs.index');
    Route::get('/souvenirs/{code_souv}', [SouvenirController::class, 'show'])->name('souvenirs.show');
    Route::get('/temoignages', [TemoignageController::class, 'index'])->name('temoignages.index');
    Route::get('/temoignages/{code_tem}', [TemoignageController::class, 'show'])->name('temoignages.show');
    Route::get('/chatbot', [ChatbotController::class, 'index'])->name('chatbot');
});

// Routes publiques (ancien système - à migrer)
// Utilisateurs
Route::get('/web/utilisateurs', [UtilisateurController::class, 'index']);
Route::get('/web/utilisateurs/create', [UtilisateurController::class, 'create']);
Route::post('/web/utilisateurs', [UtilisateurController::class, 'store']);
Route::get('/web/utilisateurs/{code_user}/edit', [UtilisateurController::class, 'edit']);
Route::put('/web/utilisateurs/{code_user}', [UtilisateurController::class, 'update']);
Route::delete('/web/utilisateurs/{code_user}', [UtilisateurController::class, 'destroy']);

// Souvenirs
Route::get('/web/souvenirs', [SouvenirController::class, 'index']);
Route::get('/web/souvenirs/create', [SouvenirController::class, 'create']);
Route::post('/web/souvenirs', [SouvenirController::class, 'store']);
Route::get('/web/souvenirs/{code_souv}/edit', [SouvenirController::class, 'edit']);
Route::put('/web/souvenirs/{code_souv}', [SouvenirController::class, 'update']);
Route::delete('/web/souvenirs/{code_souv}', [SouvenirController::class, 'destroy']);

// Témoignages
Route::get('/web/temoignages', [TemoignageController::class, 'index']);
Route::get('/web/temoignages/create', [TemoignageController::class, 'create']);
Route::post('/web/temoignages', [TemoignageController::class, 'store']);
Route::get('/web/temoignages/{code_tem}/edit', [TemoignageController::class, 'edit']);
Route::put('/web/temoignages/{code_tem}', [TemoignageController::class, 'update']);
Route::delete('/web/temoignages/{code_tem}', [TemoignageController::class, 'destroy']);

// Commentaires
Route::get('/web/commentaires', [CommentaireController::class, 'index']);
Route::get('/web/commentaires/create', [CommentaireController::class, 'create']);
Route::post('/web/commentaires', [CommentaireController::class, 'store']);
Route::get('/web/commentaires/{code_com}/edit', [CommentaireController::class, 'edit']);
Route::put('/web/commentaires/{code_com}', [CommentaireController::class, 'update']);
Route::delete('/web/commentaires/{code_com}', [CommentaireController::class, 'destroy']);

// Filières
Route::get('/web/filieres', [FiliereController::class, 'index']);
Route::get('/web/filieres/create', [FiliereController::class, 'create']);
Route::post('/web/filieres', [FiliereController::class, 'store']);
Route::get('/web/filieres/{code_fil}/edit', [FiliereController::class, 'edit']);
Route::put('/web/filieres/{code_fil}', [FiliereController::class, 'update']);
Route::delete('/web/filieres/{code_fil}', [FiliereController::class, 'destroy']);

// Promotions
Route::get('/web/promotions', [PromotionController::class, 'index']);
Route::get('/web/promotions/create', [PromotionController::class, 'create']);
Route::post('/web/promotions', [PromotionController::class, 'store']);
Route::get('/web/promotions/{code_promo}/edit', [PromotionController::class, 'edit']);
Route::put('/web/promotions/{code_promo}', [PromotionController::class, 'update']);
Route::delete('/web/promotions/{code_promo}', [PromotionController::class, 'destroy']);

// Chatbot FAQ
Route::get('/web/chatbot_faqs', [ChatbotFaqController::class, 'index']);
Route::get('/web/chatbot_faqs/create', [ChatbotFaqController::class, 'create']);
Route::post('/web/chatbot_faqs', [ChatbotFaqController::class, 'store']);
Route::get('/web/chatbot_faqs/{code_faq}/edit', [ChatbotFaqController::class, 'edit']);
Route::put('/web/chatbot_faqs/{code_faq}', [ChatbotFaqController::class, 'update']);
Route::delete('/web/chatbot_faqs/{code_faq}', [ChatbotFaqController::class, 'destroy']);

// Messages Chatbot
Route::get('/web/message_chatbots', [MessageChatbotController::class, 'index']);
Route::get('/web/message_chatbots/create', [MessageChatbotController::class, 'create']);
Route::post('/web/message_chatbots', [MessageChatbotController::class, 'store']);
Route::get('/web/message_chatbots/{code_message}/edit', [MessageChatbotController::class, 'edit']);
Route::put('/web/message_chatbots/{code_message}', [MessageChatbotController::class, 'update']);
Route::delete('/web/message_chatbots/{code_message}', [MessageChatbotController::class, 'destroy']);
