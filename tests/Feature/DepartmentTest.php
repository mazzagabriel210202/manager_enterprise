<?php

namespace Tests\Feature;

use App\Models\Department;
use App\Models\Enterprise;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class DepartmentTest extends TestCase
{
    use RefreshDatabase;

 public function test_can_list_departments(): void
    {
        $enterprise = Enterprise::factory()->create();
        // Limpa ou garante que criamos apenas um para essa enterprise, ou usa assertJsonFragment
        $department = Department::factory()->create(['enterprise_id' => $enterprise->id]);

        Sanctum::actingAs($enterprise);

        $response = $this->getJson('/api/departments');

        $response->assertStatus(200)
                 ->assertJsonFragment(['name' => $department->name]);
    }

    public function test_can_create_department(): void
    {
        $enterprise = Enterprise::factory()->create();

        // Autentica a enterprise via Sanctum
        Sanctum::actingAs($enterprise);

        $response = $this->postJson('/api/departments', [
            'name' => 'Human Resources',
            'enterprise_id' => $enterprise->id,
        ]);

        $response->assertStatus(201)
                 ->assertJsonFragment(['name' => 'Human Resources']);

        $this->assertDatabaseHas('departments', [
            'name' => 'Human Resources',
            'enterprise_id' => $enterprise->id,
        ]);
    }
}