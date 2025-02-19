<?php

namespace App\Listeners;

use App\Events\BugCreated;
use App\Models\BugLog;
use App\Notifications\Bug\BugCreatedNotification;
use App\Repositories\BugInfos\BugInfosRepositoryInterface;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Log;

class HandleBugCreated
{
    /**
     * Create the event listener.
     */
    public function __construct(protected BugInfosRepositoryInterface $bug_infos_repository)
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(BugCreated $event): void
    {
        $bug = $event->bug;
        $bugComment = $event->bugComment;
        $assigned_user = $event->assigned_user;
        $project = $event->project;
        $files = $event->files;

        $this->log_history($bug, $assigned_user);
        $this->notify_users($project, $assigned_user, $bug, $bugComment, $files);
    }

    /**
     * Log bug history
     * @param $bug
     * @param $assigned_user
     * @return void
     */
    private function log_history($bug, $assigned_user)
    {
        // The message ‘Creation of a new bug’ is logged in the bug history.
        BugLog::create([
            'bug_id'  => $bug->id,
            'user_id' => $bug->user_id,
            'action'  => __('bugtrackly.log.new_ticket_action'), //"",
        ]);

        // We log the bug's priority in the bug history
        $new_priority = $this->bug_infos_repository->getBugPriorityById($bug->priority);
        BugLog::create([
            'bug_id'  => $bug->id,
            'user_id' => $bug->user_id,
            'action'  => __('bugtrackly.tickets_list.headings.priority'), //"",
            'details' => " => " . $new_priority['label'],
        ]);

        // If the bug's status is other than ‘new’, this status is logged in the bug's history.
        if ($bug->status !== 1) {
            $new_status = $this->bug_infos_repository->getBugStatusById($bug->status);
            BugLog::create([
                'bug_id'  => $bug->id,
                'user_id' => $bug->user_id,
                'action'  => __('bugtrackly.tickets_list.headings.status'), //"",
                'details' => " => " . $new_status['label'],
            ]);
        }

        // We log the user assigned to the bug if this information is provided.
        if ($assigned_user) {
            BugLog::create([
                'bug_id'  => $bug->id,
                'user_id' => $bug->user_id,
                'action'  => __('bugtrackly.tickets_list.headings.assigned'), //"",
                'details' => " => " . $assigned_user->full_name,
            ]);
        }
    }

    /**
     * Notify user
     * @param $project
     * @param $assigned_user
     * @param $bug
     * @param $bugComment
     * @param $files
     * @return void
     */
    private function notify_users($project, $assigned_user, $bug, $bugComment, $files)
    {
        // Administrators other than the one who just wrote the bug
        $usersToNotify = $project
            ->users
            ->where('role_id', 1)
            ->where('id', '!=', $bug->user_id);

        // Assigned user
        if ($assigned_user) {
            $usersToNotify->push($assigned_user);
        }
        $usersToNotify = $usersToNotify->unique('id');

        foreach ($usersToNotify as $user) {
            $status = $this->bug_infos_repository->getBugStatusById($bug->status);
            $priority = $this->bug_infos_repository->getBugPriorityById($bug->priority);
            $user->notify(new BugCreatedNotification($project, $bug, $bugComment, $status, $priority, $files));
        }
    }
}
