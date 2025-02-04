<?php

namespace App\Listeners;

use App\Events\BugCommentCreated;
use App\Models\BugLog;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class HandleBugCommentCreated
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(BugCommentCreated $event): void
    {
        $bug = $event->bug;
        $bugComment = $event->bugComment;

        // If a note has been added to the bug, this action is logged in the bug history
        if($bugComment){
            BugLog::create([
                'bug_id' => $bug->id,
                'user_id' => $bugComment->user_id,
                'action' => __('bugtrackly.log.new_bug_comment_action'),
            ]);
        }

        // get the changes
        $change = $bug->getChanges();

    }
}
