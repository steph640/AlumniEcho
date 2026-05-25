<?php

namespace Tests\Feature;

use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Filiere;

class FiliereControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function index_returns_filieres()
    {
        $response = $this->get('/web/filieres');
        $response->assertStatus(200);
    }

    #[Test]
    public function show_returns_a_filiere()
    {
        $filiere = Filiere::factory()->create();
        $response = $this->get("/web/filieres/{$filiere->code_fil}");
        $response->assertStatus(200);
    }

    #[Test]
    public function store_creates_filiere_with_valid_data()
    {
        $data = Filiere::factory()->make()->toArray();
        $response = $this->post('/web/filieres', $data);
        $response->assertStatus(302); // redirection après création
        $this->assertDatabaseHas('filieres', ['nom_fil' => $data['nom_fil']]);
    }

    #[Test]
    public function store_fails_with_invalid_data()
    {
        $response = $this->post('/web/filieres', []);
        $response->assertSessionHasErrors();
    }

    #[Test]
    public function update_modifies_filiere()
    {
        $filiere = Filiere::factory()->create();
        $response = $this->put("/web/filieres/{$filiere->code_fil}", ['nom_fil' => 'Nouvelle filière']);
        $response->assertStatus(302);
        $this->assertDatabaseHas('filieres', ['nom_fil' => 'Nouvelle filière']);
    }

    #[Test]
    public function destroy_deletes_filiere()
    {
        $filiere = Filiere::factory()->create();
        $response = $this->delete("/web/filieres/{$filiere->code_fil}");
        $response->assertStatus(302);
        $this->assertDatabaseMissing('filieres', ['code_fil' => $filiere->code_fil]);
    }
}
