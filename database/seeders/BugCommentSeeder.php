<?php

namespace Database\Seeders;

use App\Models\Bug;
use App\Models\BugComment;
use App\Models\Project;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BugCommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $bugs = Bug::all();
        $bugs->each(function ($bug) {
            $bug->load('project');
            $project = $bug->project;

            BugComment::factory(1)->make()->each(function ($comment) use ($project, $bug) {
                $comment->bug_id = $bug->id;
                $comment->user_id = $bug->user_id;
                $comment->save();
            });


            if( fake()->boolean(75) ){
                BugComment::factory(rand(5, 15))->make()->each(function ($comment) use ($project, $bug) {
                    $comment->bug_id = $bug->id;
                    $comment->user_id = $project->users->random()->id;
                    $comment->save();
                });
            }

        });
    }
}
