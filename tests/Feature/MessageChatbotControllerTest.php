<?php

namespace Tests\Feature;

use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\MessageChatbot;

class MessageChatbotControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function index_returns_messagechatbots()
    {
        $response = $this->get('/web/message_chatbots');
        $response->assertStatus(200);
    }

    #[Test]
    public function show_returns_a_messagechatbot()
    {
        $message = MessageChatbot::factory()->create();
        $response = $this->get("/web/message_chatbots/{$message->code_message}");
        $response->assertStatus(200);
    }

    #[Test]
    public function store_creates_messagechatbot_with_valid_data()
    {
        $data = MessageChatbot::factory()->make()->toArray();
        $response = $this->post('/web/message_chatbots', $data);
        $response->assertStatus(302); // redirection après création
        $this->assertDatabaseHas('message_chatbots', ['question_chatbot' => $data['question_chatbot']]);
    }

    #[Test]
    public function store_fails_with_invalid_data()
    {
        $response = $this->post('/web/message_chatbots', []);
        $response->assertSessionHasErrors();
    }

    #[Test]
    public function update_modifies_messagechatbot()
    {
        $message = MessageChatbot::factory()->create();
        $response = $this->put("/web/message_chatbots/{$message->code_message}", ['reponse_chatbot' => 'Nouvelle réponse']);
        $response->assertStatus(302);
        $this->assertDatabaseHas('message_chatbots', ['reponse_chatbot' => 'Nouvelle réponse']);
    }

    #[Test]
    public function destroy_deletes_messagechatbot()
    {
        $message = MessageChatbot::factory()->create();
        $response = $this->delete("/web/message_chatbots/{$message->code_message}");
        $response->assertStatus(302);
        $this->assertDatabaseMissing('message_chatbots', ['code_message' => $message->code_message]);
    }
}
