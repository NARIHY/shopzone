<?php

namespace Database\Factories\Access;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Access\Role>
 */
class RoleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'roleName' => $this->faker->unique()->jobTitle(),
            'description' => $this->faker->optional()->sentence(),
            'is_active' => $this->faker->boolean(),
        ];
    }
}
