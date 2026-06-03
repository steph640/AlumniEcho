<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MessageChatbot extends Model
{
    use HasFactory;
    protected $table = 'message_chatbots';
    protected $primaryKey = 'code_message';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'code_user',
        'question_chatbot',
        'reponse_chatbot',
        'response_type',
        'is_helpful',
        'user_feedback'
    ];

    // Relations
    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class, 'code_user', 'code_user');
    }
}
