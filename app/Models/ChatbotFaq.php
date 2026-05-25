<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatbotFaq extends Model
{
    use HasFactory;
    protected $table = 'chatbot_faqs';
    protected $primaryKey = 'code_faq';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['question_faq', 'reponse_faq'];
}
