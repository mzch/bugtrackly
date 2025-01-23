<?php

namespace Database\Seeders;

use App\Models\Bug;
use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Project::factory(4)->create()->each(function ($project) {
            // Associer des utilisateurs au projet
            $users = User::all();
            $admin_user = $users->where('role_id', 1)->first();
            $rapporter_user = $users->where('role_id', 2);
            $project->users()->attach($admin_user->id);
            $project->users()->attach(
                $rapporter_user->random(rand(1, 3))->pluck('id')->toArray() // Associer 1 à 3 users
            );

        });
    }
}
