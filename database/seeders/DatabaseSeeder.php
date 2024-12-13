<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'first_name' => 'Aurélien',
            'last_name' => 'Chappard',
            'role_id' => 1,
            'email' => 'a@a.com',
            'password' =>  Hash::make('a'),
        ]);

        $this->call([
            UserSeeder::class,
        ]);

    }
}
