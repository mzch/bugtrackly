<?php

namespace Database\Factories;

use DavidBadura\FakerMarkdownGenerator\FakerProvider;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BugComment>
 */
class BugCommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'bug_id' => null,
            'user_id' => null,
            'content' => fake()->paragraph(fake()->numberBetween(1, 10)),
        ];
    }
}
