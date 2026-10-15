<?php

namespace Tests\Feature;

use App\Models\Enterprise;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class EnterpriseTest extends TestCase
{
    use RefreshDatabase;

    // --- TESTES DE SUCESSO ---

    public function test_it_can_list_enterprises(): void
    {
        $enterprise = Enterprise::factory()->create();
        Sanctum::actingAs($enterprise);

        $response = $this->getJson('/api/enterprises');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'message',
                     'data' => [
                         '*' => ['id', 'name', 'cnpj', 'created_at', 'updated_at']
                     ]
                 ]);
    }

    public function test_it_can_create_an_enterprise(): void
    {
        $enterprise = Enterprise::factory()->create();
        Sanctum::actingAs($enterprise);

        $payload = [
            'name' => 'Empresa Teste S/A',
            'cnpj' => '12345678000199',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $response = $this->postJson('/api/enterprises', $payload);

        $response->assertStatus(201)
                 ->assertJsonPath('data.name', 'Empresa Teste S/A');

        $this->assertDatabaseHas('enterprises', [
            'cnpj' => '12345678000199',
        ]);
    }

    public function test_it_can_show_an_enterprise(): void
    {
        $enterprise = Enterprise::factory()->create();
        Sanctum::actingAs($enterprise);

        $response = $this->getJson("/api/enterprises/{$enterprise->id}");

        $response->assertStatus(200)
                 ->assertJsonPath('data.id', $enterprise->id);
    }

    public function test_it_can_update_an_enterprise(): void
    {
        $enterprise = Enterprise::factory()->create();
        Sanctum::actingAs($enterprise);

        $payload = [
            'name' => 'Empresa Atualizada Ltda',
        ];

        $response = $this->putJson("/api/enterprises/{$enterprise->id}", $payload);

        $response->assertStatus(200)
                 ->assertJsonPath('data.name', 'Empresa Atualizada Ltda');

        $this->assertDatabaseHas('enterprises', [
            'id' => $enterprise->id,
            'name' => 'Empresa Atualizada Ltda',
        ]);
    }

    public function test_it_can_delete_an_enterprise(): void
    {
        $enterprise = Enterprise::factory()->create();
        Sanctum::actingAs($enterprise);

        $response = $this->deleteJson("/api/enterprises/{$enterprise->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('enterprises', [
            'id' => $enterprise->id,
        ]);
    }

    // --- TESTES DE ERRO (NEGATIVOS) ---

    public function test_unauthenticated_user_cannot_access_enterprises(): void
    {
        // Tentando acessar sem o Sanctum::actingAs
        $response = $this->getJson('/api/enterprises');

        $response->assertStatus(401);
    }

    public function test_enterprise_creation_validation_fails_when_fields_are_missing(): void
    {
        $enterprise = Enterprise::factory()->create();
        Sanctum::actingAs($enterprise);

        // Enviando payload vazio
        $response = $this->postJson('/api/enterprises', []);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['name', 'cnpj', 'password']);
    }

    public function test_enterprise_creation_fails_with_duplicate_cnpj(): void
    {
        $enterprise = Enterprise::factory()->create();
        Sanctum::actingAs($enterprise);

        // Cria uma empresa com um CNPJ específico
        Enterprise::factory()->create(['cnpj' => '12345678000199']);

        // Tenta criar outra com o mesmo CNPJ
        $payload = [
            'name' => 'Outra Empresa',
            'cnpj' => '12345678000199',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $response = $this->postJson('/api/enterprises', $payload);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['cnpj']);
    }

    public function test_it_returns_404_when_enterprise_does_not_exist(): void
    {
        $enterprise = Enterprise::factory()->create();
        Sanctum::actingAs($enterprise);

        $nonExistentId = 99999;

        // Testa o show
        $this->getJson("/api/enterprises/{$nonExistentId}")->assertStatus(404);

        // Testa o update
        $this->putJson("/api/enterprises/{$nonExistentId}", ['name' => 'Teste'])->assertStatus(404);

        // Testa o destroy
        $this->deleteJson("/api/enterprises/{$nonExistentId}")->assertStatus(404);
    }
}