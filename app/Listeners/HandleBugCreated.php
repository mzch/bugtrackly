<?php

namespace App\Listeners;

use App\Events\BugCreated;
use App\Models\BugLog;
use App\Repositories\BugInfos\BugInfosRepositoryInterface;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Log;

class HandleBugCreated
{
    /**
     * Create the event listener.
     */
    public function __construct(protected BugInfosRepositoryInterface $bug_infos_repository,)
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(BugCreated $event): void
    {
        $bug = $event->bug;
        $user = $event->assigned_user;

        // The message ‘Creation of a new bug’ is logged in the bug history.
        BugLog::create([
            'bug_id' => $bug->id,
            'user_id' => $bug->user_id,
            'action' => __('bugtrackly.log.new_bug_action'), //"",
        ]);

        // We log the bug's priority in the bug history
        $new_priority = $this->bug_infos_repository->getBugPriorityById($bug->priority);
        BugLog::create([
            'bug_id' => $bug->id,
            'user_id' => $bug->user_id,
            'action' => __('bugtrackly.bugs_list.headings.priority'), //"",
            'details' => " => " . $new_priority['label'],
        ]);

        // If the bug's status is other than ‘new’, this status is logged in the bug's history.
        if ($bug->status !== 1) {
            $new_status = $this->bug_infos_repository->getBugStatusById($bug->status);
            BugLog::create([
                'bug_id' => $bug->id,
                'user_id' => $bug->user_id,
                'action' => __('bugtrackly.bugs_list.headings.status'), //"",
                'details' => " => " . $new_status['label'],
            ]);
        }

        // We log the user assigned to the bug if this information is provided.
        if($user){
            BugLog::create([
                'bug_id' => $bug->id,
                'user_id' => $bug->user_id,
                'action' => __('bugtrackly.bugs_list.headings.assigned'), //"",
                'details' => " => " . $user->full_name,
            ]);
        }
    }
}
