<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Bug>
 */
class BugFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(),
            'status' => fake()->numberBetween(1,6),
            'priority' => fake()->numberBetween(1,5),
            'project_id' => null, // Défini dans le seeder
            'user_id' => null,    // Défini dans le seeder
        ];
    }
}
