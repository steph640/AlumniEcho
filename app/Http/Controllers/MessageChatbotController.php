<?php

namespace App\Http\Controllers;

use App\Models\MessageChatbot;
use Illuminate\Http\Request;

class MessageChatbotController extends Controller
{
    public function index()
    {
        return response()->json(MessageChatbot::with(['utilisateur'])->get());
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code_message' => 'required|string|unique:message_chatbots',
            'code_user' => 'required|exists:utilisateurs,code_user',
            'question_chatbot' => 'required|string',
            'reponse_chatbot' => 'sometimes|string|nullable',

        ]);

        $message = MessageChatbot::create($validated);
        return response()->json($message,201);
    }

    public function show(MessageChatbot $messageChatbot)
    {
        return response()->json($messageChatbot);
    }
    public function update(Request $request, MessageChatbot $messageChatbot)
    {
        $validated = $request->validate([
            'question_chatbot' => 'sometimes|string',
            'reponse_chatbot' => 'sometimes|string',
            'code_user' => 'sometimes|exists:utilisateurs,code_user'
        ]);

        $messageChatbot->update($validated);
        return response()->json($messageChatbot);
    }

    public function destroy(MessageChatbot $messageChatbot)
    {
        $messageChatbot->delete();
        return response()->json(["message" => "Message supprimée!"]);
    }
}
