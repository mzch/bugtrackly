<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $firstName = fake()->firstName();
        $lastName = fake()->lastName();

        // Liste des images dans le répertoire des photos de profil
        $photos = Storage::disk('public')->allFiles('profile-photos');
        array_shift($photos); //exclusion du fichier ".gitkeep"

        // Générer un email unique en ajoutant un numéro aléatoire
        $email = Str::slug($firstName) . '.' . Str::slug($lastName) . '@' . fake()->safeEmailDomain();
        return [
            'first_name' => $firstName,
            'last_name' => $lastName,
            'role_id' => 2,
            'email' => $email,
            'email_verified_at' => now(),
            'profile_photo_path' => fake()->boolean(40) // 70% des utilisateurs auront une photo
                ? (count($photos) ? fake()->randomElement($photos) : null)
                : null,
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
