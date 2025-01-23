<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Récupérer tous les utilisateurs et projets
        $users = User::all();
        $admin_user = $users->where('role_id', 1)->first();
        $rapporter_user = $users->where('role_id', 2);
        $projects = Project::all();

        $projects->each(function ($project) use ($admin_user, $rapporter_user) {
            $project->users()->attach($admin_user->id);
            $project->users()->attach(
                $rapporter_user->random(rand(1, 3))->pluck('id')->toArray() // Associer 1 à 3 users
            );
        });
    }
}
