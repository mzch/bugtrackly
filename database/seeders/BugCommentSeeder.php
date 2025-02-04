<?php

namespace Database\Seeders;

use App\Events\BugCommentCreated;
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


            if( fake()->boolean(75) ){
                BugComment::factory(rand(2, 10))->make()->each(function ($comment) use ($project, $bug) {
                    $randomUser = $project->users->random();
                    $comment->bug_id = $bug->id;
                    $comment->user_id = $randomUser->id;
                    $comment->save();
                    BugCommentCreated::dispatch($project, $comment, $bug, [],[],$randomUser);
                });

            }

        });
    }
}
