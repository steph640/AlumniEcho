<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\ChatbotFaq;

class ChatbotController extends Controller
{
    public function index()
    {
        $faqs = ChatbotFaq::all();
        return view('chatbot.index', compact('faqs'));
    }
}
