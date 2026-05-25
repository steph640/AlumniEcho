<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChatbotFaqController;
use App\Http\Controllers\CommentaireController;
use App\Http\Controllers\FiliereController;
use App\Http\Controllers\MessageChatbotController;
use App\Http\Controllers\PromotionController;
use App\Http\Controllers\SouvenirController;
use App\Http\Controllers\TemoignageController;
use App\Http\Controllers\UtilisateurController;
use App\Http\Controllers\Api\ChatbotController;
use App\Models\Utilisateur;


// Routes publiques (auth)
Route::post('/login',[AuthController::class,'login']);
Route::post('/register',[AuthController::class,'register']);

// Routes publiques (lecture seule)
Route::resource('utilisateurs', UtilisateurController::class)->only(['index','show']);
Route::resource('souvenirs', SouvenirController::class)->only(['index','show']);
Route::resource('temoignages', TemoignageController::class)->only(['index','show']);
Route::resource('commentaires', CommentaireController::class)->only(['index','show']);
Route::resource('filieres', FiliereController::class)->only(['index','show']);
Route::resource('promotions', PromotionController::class)->only(['index','show']);
Route::resource('chatbot-faqs', ChatbotFaqController::class)->only(['index','show']);
Route::resource('message-chatbots', MessageChatbotController::class)->only(['index','show']);

// Chatbot endpoints
Route::get('/chatbot/faqs', [ChatbotController::class, 'getFaqs']);
Route::post('/chatbot/ask', [ChatbotController::class, 'ask']);

// Routes protégées
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout',[AuthController::class,'logout']);

    Route::resource('utilisateurs', UtilisateurController::class)->only(['store','update','destroy']);
    Route::resource('souvenirs', SouvenirController::class)->only(['store','update','destroy']);
    Route::resource('temoignages', TemoignageController::class)->only(['store','update','destroy']);
    Route::resource('commentaires', CommentaireController::class)->only(['store','update','destroy']);
    Route::resource('filieres', FiliereController::class)->only(['store','update','destroy']);
    Route::resource('promotions', PromotionController::class)->only(['store','update','destroy']);
    Route::resource('chatbot-faqs', ChatbotFaqController::class)->only(['store','update','destroy']);
    Route::resource('message-chatbots', MessageChatbotController::class)->only(['store','update','destroy']);
    
    // Chatbot history
    Route::get('/chatbot/history', [ChatbotController::class, 'getHistory']);
});
