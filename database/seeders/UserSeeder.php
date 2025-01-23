<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'first_name'         => 'User',
            'last_name'          => 'Admin',
            'role_id'            => 1,
            'email'              => 'a@a.com',
            'profile_photo_path' => 'profile-photos/faker/aurelien.jpg',
            'password'           => Hash::make('a'),
        ]);
        User::factory(29)->create();
    }
}
