<?php

namespace Database\Seeders;

use App\Models\Bug;
use App\Models\Project;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class BugSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $projects = Project::all();
        $projects->each(function ($project) {
            $project->load('users');
            Bug::factory(rand(5, 15))->make()->each(function ($bug) use ($project) {
                $bug->project_id = $project->id;
                $bug->user_id = $project->users->random()->id;
                $bug->assigned_user_id = fake()->boolean(50) ? $project->users->random()->id : null;
                $bug->save();
            });
        });
    }
}
