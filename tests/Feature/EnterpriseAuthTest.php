<?php

namespace Tests\Feature;

use App\Models\Enterprise;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EnterpriseAuthTest extends TestCase
{
    use RefreshDatabase; // Reseta o banco de dados a cada teste

    public function test_can_register_an_enterprise(): void
    {
        $response = $this->postJson('/api/enterprise/register', [
            'name' => 'Empresa Teste Ltda',
            'cnpj' => '12345678000199',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(201)
                 ->assertJsonStructure([
                     'message',
                     'data' => [
                         'id',
                         'name',
                         'cnpj',
                         'created_at',
                         'updated_at',
                     ]
                 ]);

        // Verifica se a empresa foi realmente salva no banco
        $this->assertDatabaseHas('enterprises', [
            'name' => 'Empresa Teste Ltda',
            'cnpj' => '12345678000199',
        ]);
    }

    public function test_can_login_with_valid_credentials(): void
    {
        // Cria uma empresa de mentira no banco para o teste
        $enterprise = Enterprise::create([
            'name' => 'Empresa Login',
            'cnpj' => '98765432000188',
            'password' => 'password123', // O cast 'hashed' do model cuida do hash
        ]);

        $response = $this->postJson('/api/enterprise/login', [
            'name' => 'Empresa Login',
            'password' => 'password123',
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'message',
                     'data',
                     'token' // Garante que o Sanctum gerou o token
                 ]);
    }

    public function test_cannot_login_with_invalid_password(): void
    {
        Enterprise::create([
            'name' => 'Empresa Login',
            'cnpj' => '98765432000188',
            'password' => 'password123',
        ]);

        $response = $this->postJson('/api/enterprise/login', [
            'name' => 'Empresa Login',
            'password' => 'senha-errada',
        ]);

        // Espera erro de validação (422) retornado pelo Service
        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['credentials']);
    }

    public function test_can_logout_when_authenticated(): void
    {
        $enterprise = Enterprise::create([
            'name' => 'Empresa Logout',
            'cnpj' => '11223344000155',
            'password' => 'password123',
        ]);

        // Autentica gerando um token do Sanctum para o teste
        $token = $enterprise->createToken('test_token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->postJson('/api/enterprise/logout');

        // Espera sucesso (200) no logout
        $response->assertStatus(200)
                 ->assertJson([
                     'message' => 'Logout realizado com sucesso!'
                 ]);
    }
}