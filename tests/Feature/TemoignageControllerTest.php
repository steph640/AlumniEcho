<?php

namespace Tests\Feature;

use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Temoignage;

class TemoignageControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function index_returns_temoignages()
    {
        $response = $this->get('/web/temoignages');
        $response->assertStatus(200);
    }

    #[Test]
    public function show_returns_a_temoignage()
    {
        $temoignage = Temoignage::factory()->create();
        $response = $this->get("/web/temoignages/{$temoignage->code_tem}");
        $response->assertStatus(200);
    }

    #[Test]
    public function store_creates_temoignage_with_valid_data()
    {
        $data = Temoignage::factory()->make()->toArray();
        $response = $this->post('/web/temoignages', $data);
        $response->assertStatus(302); // redirection après création
        $this->assertDatabaseHas('temoignages', ['contenu_tem' => $data['contenu_tem']]);
    }

    #[Test]
    public function store_fails_with_invalid_data()
    {
        $response = $this->post('/web/temoignages', []);
        $response->assertSessionHasErrors();
    }

    #[Test]
    public function update_modifies_temoignage()
    {
        $temoignage = Temoignage::factory()->create();
        $response = $this->put("/web/temoignages/{$temoignage->code_tem}", ['contenu_tem' => 'Texte modifié']);
        $response->assertStatus(302);
        $this->assertDatabaseHas('temoignages', ['contenu_tem' => 'Texte modifié']);
    }

    #[Test]
    public function destroy_deletes_temoignage()
    {
        $temoignage = Temoignage::factory()->create();
        $response = $this->delete("/web/temoignages/{$temoignage->code_tem}");
        $response->assertStatus(302);
        $this->assertDatabaseMissing('temoignages', ['code_tem' => $temoignage->code_tem]);
    }
}
