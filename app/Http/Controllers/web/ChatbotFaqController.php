<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\ChatbotFaq;
use Illuminate\Http\Request;

class ChatbotFaqController extends Controller
{
     public function index()
    {
        try {
            $faqs = ChatbotFaq::paginate(10);
            return view('chatbot_faqs.index', compact('faqs'));
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors du chargement : ' . $e->getMessage());
        }
    }

    public function create()
    {
        return view('chatbot_faqs.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code_faq' => 'required|string|max:15|unique:chatbot_faqs,code_faq',
            'question_faq' => 'required|string|max:500',
            'reponse_faq' => 'required|string',
        ]);

        try {
            ChatbotFaq::create($validated);
            return redirect('/web/chatbot_faqs')->with('success', 'FAQ créée avec succès!');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Erreur lors de la création : ' . $e->getMessage());
        }
    }

    public function edit($code_faq)
    {
        try {
            $faq = ChatbotFaq::findOrFail($code_faq);
            return view('chatbot_faqs.edit', compact('faq'));
        } catch (\Exception $e) {
            return back()->with('error', 'FAQ introuvable : ' . $e->getMessage());
        }
    }

    public function update(Request $request, $code_faq)
    {
        try {
            $faq = ChatbotFaq::findOrFail($code_faq);

            $validated = $request->validate([
                'question_faq' => 'sometimes|required|string|max:500',
                'reponse_faq' => 'sometimes|required|string',
            ]);

            $faq->update($validated);
            return redirect('/web/chatbot_faqs')->with('success', 'FAQ mise à jour avec succès!');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Erreur lors de la mise à jour : ' . $e->getMessage());
        }
    }

    public function destroy($code_faq)
    {
        try {
            $faq = ChatbotFaq::findOrFail($code_faq);
            $faq->delete();
            return redirect('/web/chatbot_faqs')->with('success', 'FAQ supprimée avec succès!');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la suppression : ' . $e->getMessage());
        }
    }
}
