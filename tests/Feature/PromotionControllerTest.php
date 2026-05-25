<?php

namespace Tests\Feature;

use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Promotion;

class PromotionControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function index_returns_promotions()
    {
        $response = $this->get('/web/promotions');
        $response->assertStatus(200);
    }

    #[Test]
    public function show_returns_a_promotion()
    {
        $promotion = Promotion::factory()->create();
        $response = $this->get("/web/promotions/{$promotion->code_pro}");
        $response->assertStatus(200);
    }

    #[Test]
    public function store_creates_promotion_with_valid_data()
    {
        $data = Promotion::factory()->make()->toArray();
        $response = $this->post('/web/promotions', $data);
        $response->assertStatus(302); // redirection après création
        $this->assertDatabaseHas('promotions', ['nom_pro' => $data['nom_pro']]);
    }

    #[Test]
    public function store_fails_with_invalid_data()
    {
        $response = $this->post('/web/promotions', []);
        $response->assertSessionHasErrors();
    }

    #[Test]
    public function update_modifies_promotion()
    {
        $promotion = Promotion::factory()->create();
        $response = $this->put("/web/promotions/{$promotion->code_pro}", ['nom_pro' => 'Nouvelle promo']);
        $response->assertStatus(302);
        $this->assertDatabaseHas('promotions', ['nom_pro' => 'Nouvelle promo']);
    }

    #[Test]
    public function destroy_deletes_promotion()
    {
        $promotion = Promotion::factory()->create();
        $response = $this->delete("/web/promotions/{$promotion->code_pro}");
        $response->assertStatus(302);
        $this->assertDatabaseMissing('promotions', ['code_pro' => $promotion->code_pro]);
    }
}
