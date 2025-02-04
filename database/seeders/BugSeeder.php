<?php

namespace Database\Seeders;

use App\Events\BugCreated;
use App\Models\Bug;
use App\Models\BugComment;
use App\Models\Project;
use Illuminate\Database\Seeder;

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
                $assigned_user = fake()->boolean(80) ? $project->users->random() : null;

                $bug->project_id = $project->id;
                $bug->user_id = $project->users->random()->id;
                $bug->assigned_user_id = $assigned_user ? $assigned_user->id : null;
                $bug->save();

                // Bug description
                $bugComment = BugComment::factory()->create([
                    'bug_id'         => $bug->id,
                    'user_id'          => $bug->user_id,
                ]);

                BugCreated::dispatch($project, $bug, $bugComment, $assigned_user, []);
            });
        });
    }
}
