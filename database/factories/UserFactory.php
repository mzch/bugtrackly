<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
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
        $randomNumber = fake()->numberBetween(1, 100);
        if ($randomNumber <= 5) {
            $role_id = 1;  // 5% chance d'être admin
        }  else{
            $role_id = 2;  // 95% de chance d'être apporteur d'affaire externe
        }
        $firstName = fake()->firstName();
        $lastName = fake()->lastName();

        // Générer un email unique en ajoutant un numéro aléatoire
        $email = Str::slug($firstName) . '.' . Str::slug($lastName) . '@' . fake()->safeEmailDomain();
        return [
            'first_name' => $firstName,
            'last_name' => $lastName,
            'role_id' => $role_id,
            'email' => $email,
            'email_verified_at' => now(),
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
