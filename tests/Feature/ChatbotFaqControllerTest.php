<?php

namespace Tests\Feature;

use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\ChatbotFaq;

class ChatbotFaqControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function index_returns_chatbotfaqs()
    {
        $response = $this->get('/web/chatbot_faqs');
        $response->assertStatus(200);
    }

    #[Test]
    public function show_returns_a_chatbotfaq()
    {
        $faq = ChatbotFaq::factory()->create();
        $response = $this->get("/web/chatbot_faqs/{$faq->code_faq}");
        $response->assertStatus(200);
    }

    #[Test]
    public function store_creates_chatbotfaq_with_valid_data()
    {
        $data = ChatbotFaq::factory()->make()->toArray();
        $response = $this->post('/web/chatbot_faqs', $data);
        $response->assertStatus(302); // redirection après création
        $this->assertDatabaseHas('chatbot_faqs', ['question' => $data['question']]);
    }

    #[Test]
    public function store_fails_with_invalid_data()
    {
        $response = $this->post('/web/chatbot_faqs', []);
        $response->assertSessionHasErrors();
    }

    #[Test]
    public function update_modifies_chatbotfaq()
    {
        $faq = ChatbotFaq::factory()->create();
        $response = $this->put("/web/chatbot_faqs/{$faq->code_faq}", ['reponse' => 'Réponse mise à jour']);
        $response->assertStatus(302);
        $this->assertDatabaseHas('chatbot_faqs', ['reponse' => 'Réponse mise à jour']);
    }

    #[Test]
    public function destroy_deletes_chatbotfaq()
    {
        $faq = ChatbotFaq::factory()->create();
        $response = $this->delete("/web/chatbot-faqs/{$faq->code_faq}");
        $response->assertStatus(302);
        $this->assertDatabaseMissing('chatbot_faqs', ['code_faq' => $faq->code_faq]);
    }
}
