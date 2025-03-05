<?php

namespace App\Listeners;

use App\Events\BugCommentCreated;
use App\Models\BugLog;
use App\Notifications\Bug\BugCommentCreatedNotification;
use App\Repositories\BugInfos\BugInfosRepositoryInterface;
use App\Repositories\TicketCategory\TicketCategoriesRepository;
use App\Repositories\Users\UserRepositoryInterface;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class HandleBugCommentCreated
{
    /**
     * Create the event listener.
     */
    public function __construct(
        protected BugInfosRepositoryInterface $bug_infos_repository,
        protected UserRepositoryInterface     $user_repository,
        protected TicketCategoriesRepository $ticketCategoriesRepository
    )
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(BugCommentCreated $event): void
    {
        $project = $event->project;
        $bug = $event->bug;
        $original = $event->original;
        $bugComment = $event->bugComment;
        $files = $event->files;
        $cliUser = $event->cliUser;

        $dataMail = [
            'note_author' => $cliUser ?: auth()->user(),
            'new_comment' => $bugComment,
            'priority' => false,
            'status' => false,
            'assigned_user' => false,
            'category' => false,
            'files' => $files,
        ];

        // If a note has been added to the bug, this action is logged in the bug history
        if ($bugComment) {
            BugLog::create([
                'bug_id'  => $bug->id,
                'user_id' => $bugComment->user_id,
                'action'  => __('bugtrackly.log.new_ticket_comment_action'),
            ]);
        }

        // get the changes
        $change = $bug->getChanges();

        // Has the priority of the bug changed?
        if (array_key_exists("priority", $change)) {
            $old_priority = $this->bug_infos_repository->getBugPriorityById($original["priority"]);
            $new_priority = $this->bug_infos_repository->getBugPriorityById($change["priority"]);
            $dataMail['priority'] = ['old' => $old_priority, 'new' => $new_priority];

            BugLog::create([
                'bug_id'  => $bug->id,
                'user_id' => auth()->id(),
                'action'  => __('bugtrackly.tickets_list.headings.priority'),
                'details' => $old_priority['label'] . " => " . $new_priority['label']
            ]);
        }

        // Has the status of the bug changed?
        if (array_key_exists("status", $change)) {
            $old_status = $this->bug_infos_repository->getBugStatusById($original["status"]);
            $new_status = $this->bug_infos_repository->getBugStatusById($change["status"]);
            $dataMail['status'] = ['old' => $old_status, 'new' => $new_status];

            BugLog::create([
                'bug_id'  => $bug->id,
                'user_id' => auth()->id(),
                'action'  => __('bugtrackly.tickets_list.headings.status'),
                'details' => $old_status['label'] . " => " . $new_status['label']
            ]);
        }

        // todo :Has the category of the bug changed ?
        if (array_key_exists("ticket_category_id", $change)) {
            $old_cat = null;
            if ($original["ticket_category_id"]) {
                $old_cat = $this->ticketCategoriesRepository->getTicketCategoryById($original["ticket_category_id"]);
            }
            $new_cat = null;
            if ($change["ticket_category_id"]) {
                $new_cat = $this->ticketCategoriesRepository->getTicketCategoryById($change["ticket_category_id"]);
            }
            $dataMail['category'] = ['old' => $old_cat, 'new' => $new_cat];

            if ($old_cat && $new_cat) {
                $str = $old_cat->name . " => " . $new_cat->name;
            } elseif ($old_cat === null && $new_cat) {
                $str = " => " . $new_cat->name;
            } else {
                $str = $old_cat->name . " => (" . __('bugtrackly.ticket_cat_none') . ")";
            }
            //
            BugLog::create([
                'bug_id'  => $bug->id,
                'user_id' => auth()->id(),
                'action'  => __('bugtrackly.tickets_list.headings.category'),
                'details' => $str
            ]);
        }

        // Has the assigned user of the bug changed?
        $oldUserAssigned = $bug->assigned_user;
        if (array_key_exists("assigned_user_id", $change)) {
            $old_user = null;
            if ($original["assigned_user_id"]) {
                $old_user = $this->user_repository->getUserById($original["assigned_user_id"]);
            }
            $new_user = null;
            if ($change["assigned_user_id"]) {
                $new_user = $this->user_repository->getUserById($change["assigned_user_id"]);
            }
            $dataMail['assigned_user'] = ['old' => $old_user, 'new' => $new_user];
            if ($old_user && $new_user) {
                $str = $old_user->full_name . " => " . $new_user->full_name;
            } elseif ($old_user === null && $new_user) {
                $str = " => " . $new_user->full_name;
            } else {
                $str = $old_user->full_name . " => (" . __('bugtrackly.tickets_list.not_assigned') . ")";
            }
            BugLog::create([
                'bug_id'  => $bug->id,
                'user_id' => auth()->id(),
                'action'  => __('bugtrackly.tickets_list.headings.assigned'),
                'details' => $str
            ]);
        }

        // Emails notifications
        $usersToNotify = $project
            ->users->where('role_id', 1)
            ->where('id', '!=', auth()->id()); // les admins sur le projets autre que le user connecté
        if($dataMail['assigned_user'] && $dataMail['assigned_user']['new']){
            $usersToNotify->push($dataMail['assigned_user']['new']);
        }
        if($oldUserAssigned){
            $usersToNotify->push($oldUserAssigned);
        }

        $usersToNotify = $usersToNotify->concat($bug->user_followers);
        $usersToNotify = $usersToNotify->unique('id');
        foreach ($usersToNotify as $user) {
            $user->notify(new BugCommentCreatedNotification($project, $bug, $dataMail));
        }
    }
}
