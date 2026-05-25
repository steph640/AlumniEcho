<?php

namespace Tests\Feature;

use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Commentaire;

class CommentaireControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function index_returns_commentaires()
    {
        $response = $this->get('/web/commentaires');
        $response->assertStatus(200);
    }

    #[Test]
    public function show_returns_a_commentaire()
    {
        $commentaire = Commentaire::factory()->create();
        $response = $this->get("/web/commentaires/{$commentaire->code_com}");
        $response->assertStatus(200);
    }

    #[Test]
    public function store_creates_commentaire_with_valid_data()
    {
        $data = Commentaire::factory()->make()->toArray();
        $response = $this->post('/web/commentaires', $data);
        $response->assertStatus(302); // redirection après création
        $this->assertDatabaseHas('commentaires', ['contenu_com' => $data['contenu_com']]);
    }

    #[Test]
    public function store_fails_with_invalid_data()
    {
        $response = $this->post('/web/commentaires', []);
        $response->assertSessionHasErrors();
    }

    #[Test]
    public function update_modifies_commentaire()
    {
        $commentaire = Commentaire::factory()->create();
        $response = $this->put("/web/commentaires/{$commentaire->code_com}", ['contenu_com' => 'Texte modifié']);
        $response->assertStatus(302);
        $this->assertDatabaseHas('commentaires', ['contenu_com' => 'Texte modifié']);
    }

    #[Test]
    public function destroy_deletes_commentaire()
    {
        $commentaire = Commentaire::factory()->create();
        $response = $this->delete("/web/commentaires/{$commentaire->code_com}");
        $response->assertStatus(302);
        $this->assertDatabaseMissing('commentaires', ['code_com' => $commentaire->code_com]);
    }
}
