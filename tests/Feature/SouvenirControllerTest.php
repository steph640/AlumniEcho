<?php

namespace Tests\Feature;

use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Souvenir; // ⚠️ adapte selon le contrôleur

class SouvenirControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function index_returns_souvenirs()
    {
        $response = $this->get('/web/souvenirs');
        $response->assertStatus(200);
    }

    #[Test]
    public function show_returns_a_souvenir()
    {
        $souvenir = Souvenir::factory()->create();
        $response = $this->get("/web/souvenirs/{$souvenir->code_souv}");
        $response->assertStatus(200);
    }

    #[Test]
    public function store_creates_souvenir_with_valid_data()
    {
        $data = Souvenir::factory()->make()->toArray();
        $response = $this->post('/web/souvenirs', $data);
        $response->assertStatus(302);
        $this->assertDatabaseHas('souvenirs', ['titre_souv' => $data['titre_souv']]);
    }

    #[Test]
    public function store_fails_with_invalid_data()
    {
        $response = $this->post('/web/souvenirs', []);
        $response->assertSessionHasErrors();
    }

    #[Test]
    public function update_modifies_souvenir()
    {
        $souvenir = Souvenir::factory()->create();
        $response = $this->put("/web/souvenirs/{$souvenir->code_souv}", ['titre_souv' => 'Nouveau titre']);
        $response->assertStatus(302);
        $this->assertDatabaseHas('souvenirs', ['titre_souv' => 'Nouveau titre']);
    }

    #[Test]
    public function destroy_deletes_souvenir()
    {
        $souvenir = Souvenir::factory()->create();
        $response = $this->delete("/web/souvenirs/{$souvenir->code_souv}");
        $response->assertStatus(302);
        $this->assertDatabaseMissing('souvenirs', ['code_souv' => $souvenir->code_souv]);
    }
}
