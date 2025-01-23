<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Project>
 */
class ProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Liste des images dans le répertoire des photos de profil
        $photos = Storage::disk('public')->allFiles('project-photos');
        array_shift($photos); //exclusion du fichier ".gitkeep"

        $name = fake()->company();
        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'short_desc' => fake()->sentence(),
            'project_photo_path' => count($photos) ? fake()->randomElement($photos) : null,
        ];
    }
}
