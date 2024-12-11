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
            'first_name' => 'Aurélien',
            'last_name' => 'Chappard',
            'role_id' => 1,
            'email' => 'a@a.com',
            'password' =>  Hash::make('a'),
        ]);
        User::factory(9)->create();
    }
}
