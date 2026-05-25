<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\MessageChatbot;
use Illuminate\Http\Request;

class MessageChatbotController extends Controller
{
    public function index()
    {
        try {
            $messages = MessageChatbot::paginate(10);
            return view('message_chatbots.index', compact('messages'));
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors du chargement : ' . $e->getMessage());
        }
    }

    public function create()
    {
        return view('message_chatbots.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code_message' => 'required|string|max:15|unique:message_chatbots,code_message',
            'question_chatbot' => 'required|string',
            'reponse_chatbot' => 'sometimes|string|nullable',
            'code_user' => 'required|exists:utilisateurs,code_user',
        ]);

        try {
            MessageChatbot::create($validated);
            return redirect('/web/message_chatbots')->with('success', 'Message créé avec succès!');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Erreur lors de la création : ' . $e->getMessage());
        }
    }

    public function edit($code_message)
    {
        try {
            $message = MessageChatbot::findOrFail($code_message);
            return view('message_chatbots.edit', compact('message'));
        } catch (\Exception $e) {
            return back()->with('error', 'Message introuvable : ' . $e->getMessage());
        }
    }

    public function update(Request $request, $code_message)
    {
        try {
            $message = MessageChatbot::findOrFail($code_message);

            $validated = $request->validate([
                'question_chatbot' => 'sometimes|required|string',
                'reponse_chatbot' => 'sometimes|nullable|string',
                'code_user' => 'sometimes|required|exists:utilisateurs,code_user',
            ]);

            $message->update($validated);
            return redirect('/web/message_chatbots')->with('success', 'Message mis à jour!');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Erreur lors de la mise à jour : ' . $e->getMessage());
        }
    }

    public function destroy($code_message)
    {
        try {
            $message = MessageChatbot::findOrFail($code_message);
            $message->delete();
            return redirect('/web/message_chatbots')->with('success', 'Message supprimé!');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la suppression : ' . $e->getMessage());
        }
    }
}
