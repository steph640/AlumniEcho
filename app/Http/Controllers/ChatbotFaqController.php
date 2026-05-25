<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ChatbotFaq;

class ChatbotFaqController extends Controller
{
    //liste des FAQs
    public function index()
    {
        return response()->json(ChatbotFaq::all());
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code_faq' => 'required|string|unique:chatbot_faqs',
            'question_faq' => 'required|string',
            'reponse_faq' => 'required|string',
        ]);

        $faq = ChatbotFaq::create($validated);
        return response()->json($faq,201);
    }

    public function show(ChatbotFaq $chatbotFaq)
    {
        return response()->json($chatbotFaq);
    }
    public function update(Request $request, ChatbotFaq $chatbotFaq)
    {
        $validated = $request->validate([
            'question_faq' => 'sometimes|string',
            'reponse_faq' => 'sometimes|string',
        ]);

        $chatbotFaq->update($validated);
        return response()->json($chatbotFaq);
    }

    public function destroy(ChatbotFaq $chatbotFaq)
    {
        $chatbotFaq->delete();
        return response()->json(["message" => "FAQ supprimée!"]);
    }
}
