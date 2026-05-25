<?php

namespace Tests\Feature;

use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Utilisateur;

class UtilisateurControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_returns_utilisateurs()
    {
        $response = $this->get('/web/utilisateurs');
        $response->assertStatus(200);
    }

    public function test_show_returns_utilisateur()
    {
        $utilisateur = Utilisateur::factory()->create();
        $response = $this->get("/web/utilisateurs/{$utilisateur->code_util}");
        $response->assertStatus(200);
    }

    public function test_store_creates_utilisateur_with_valid_data()
    {
        $data = Utilisateur::factory()->make()->toArray();
        $response = $this->post('/web/utilisateurs', $data);
        $response->assertStatus(302);
        $this->assertDatabaseHas('utilisateurs', ['nom_util' => $data['nom_util']]);
    }

    public function test_store_fails_with_invalid_data()
    {
        $response = $this->post('/web/utilisateurs', []);
        $response->assertSessionHasErrors();
    }

    public function test_update_modifies_utilisateur()
    {
        $utilisateur = Utilisateur::factory()->create();
        $response = $this->put("/web/utilisateurs/{$utilisateur->code_util}", ['nom_util' => 'Nouveau nom']);
        $response->assertStatus(302);
        $this->assertDatabaseHas('utilisateurs', ['nom_util' => 'Nouveau nom']);
    }

    public function test_destroy_deletes_utilisateur()
    {
        $utilisateur = Utilisateur::factory()->create();
        $response = $this->delete("/web/utilisateurs/{$utilisateur->code_util}");
        $response->assertStatus(302);
        $this->assertDatabaseMissing('utilisateurs', ['code_util' => $utilisateur->code_util]);
    }
}
