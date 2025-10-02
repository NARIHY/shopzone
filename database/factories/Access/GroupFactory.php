<?php

namespace Database\Factories\Access;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Access\Group>
 */
class GroupFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->jobTitle(),
            'description' => $this->faker->optional()->sentence(),
            'role_id' => \App\Models\Access\Role::factory(),
            'is_active' => $this->faker->boolean(),
        ];
    }
}
