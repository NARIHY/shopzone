<?php

namespace Database\Factories\Files;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Files\Media>
 */
class MediaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'path' => $this->faker->filePath(),
            'original_name' => $this->faker->optional()->word(),
            'disk' => 'public',
            'mime_type' => $this->faker->optional()->mimeType(),
            'size' => $this->faker->optional()->numberBetween(1000, 1000000),
        ];
    }
}
