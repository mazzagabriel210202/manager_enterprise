<?php

namespace Database\Factories;

use App\Models\Enterprise;
use Illuminate\Database\Eloquent\Factories\Factory;

class DepartmentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->jobTitle() . ' Dept',
            'enterprise_id' => Enterprise::factory(),
        ];
    }
}