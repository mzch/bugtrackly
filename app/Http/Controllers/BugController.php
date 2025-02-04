<?php

namespace App\Http\Controllers;

use App\Events\BugCreated;
use App\Http\Requests\Bug\StoreBugRequest;
use App\Http\Requests\Bug\UpdateBugPriorityRequest;
use App\Http\Requests\Bug\UpdateBugRequest;
use App\Http\Requests\Bug\UpdateBugStatusRequest;
use App\Models\Bug;
use App\Models\BugComment;
use App\Models\Project;
use App\Models\User;
use App\Notifications\Bug\BugCreatedNotification;
use App\Repositories\BugInfos\BugInfosRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Response;

class BugController extends Controller
{

    public function __construct(
        protected BugInfosRepositoryInterface $bug_infos_repository,
    )
    {
    }

    /**
     * Create bug page
     * @param Project $project
     * @return Response
     */
    public function create(Project $project): Response
    {
        $project->load('users');
        $this->addBreadcrumb($project->name, route('projects.show', $project));
        $this->addBreadcrumb("Création d'un bug", false);
        $data = [
            'project'        => $project,
            'bug_status'     => $this->bug_infos_repository->getAllBugStatus(),
            'bug_priorities' => $this->bug_infos_repository->getAllBugPriorities()
        ];

        return $this->render('Bug/BugCreate', $data);
    }

    /**
     * Store the bug
     *  - Store bug title and infos (status, assigned user and more)
     *  - store first comment
     *  - Log history
     *  - Notify concerned user(s)
     * @param StoreBugRequest $request
     * @param Project $project
     * @return RedirectResponse
     */
    public function store(StoreBugRequest $request, Project $project): RedirectResponse
    {
        // Nouveau bug
        $bug = new Bug($request->validated());

        // Nouveau commentaire (description du bug)
        $bugComment = new BugComment($request->validated());

        // Enregistrement du bug et de son premier commentaire
        $project->bugs()->save($bug);
        $bug->bug_comments()->save($bugComment);

        // Associer les fichiers
        $files = BugCommentFileController::do_upload_files($request, $bugComment);

        $assignedUser = null;
        if ($request->validated('assigned_user_id')) {
            $assignedUser = User::where('id', $request->validated('assigned_user_id'))->first();
        }
        // Déclencher l'événement
        BugCreated::dispatch($bug, $assignedUser);


        // email notif
        $usersToNotify = $project
            ->users
            ->where('role_id', 1)
            ->where('id', '!=', auth()->id()); // les admins sur le projets autre que celui-connecté
        if ($assignedUser) {
            $usersToNotify->push($assignedUser);
        }
        $usersToNotify = $usersToNotify->unique('id');
        foreach ($usersToNotify as $user) {
            $status = $this->bug_infos_repository->getBugStatusById($bug->status);
            $priority = $this->bug_infos_repository->getBugPriorityById($bug->priority);
            $user->notify(new BugCreatedNotification($project, $bug, $bugComment, $status, $priority, $files));
        }

        $flash_notification = [
            "title" => __('flash_bugtrackly.bug_created_title'),
            "text"  => __('flash_bugtrackly.bug_created_desc', ['bug_id' => $bug->id])
        ];
        return to_route('projects.bug.show', [$project, $bug])->with('success', $flash_notification);
    }

    /**
     * View the bug page
     * @param Project $project
     * @param Bug $bug
     * @return Response
     */
    public function show(Project $project, Bug $bug): Response
    {
        $project->load('users');
        $bug->load(['bug_comments', 'bug_logs', 'user_followers']);
        $this->addBreadcrumb($project->name, route('projects.show', $project));
        $this->addBreadcrumb('Bug n°' . $bug->bug_id_formatted, route('projects.show', $project));

        // Vérifie si l'utilisateur connecté suit ce bug
        $isFollowing = $bug->user_followers->contains(auth()->id());

        $data = [
            'project'        => $project,
            'bug'            => $bug,
            'isFollowing'    => $isFollowing,
            'bug_status'     => $this->bug_infos_repository->getAllBugStatus(),
            'bug_priorities' => $this->bug_infos_repository->getAllBugPriorities()
        ];
        return $this->render('Bug/BugShow', $data);
    }

    /**
     * Update the bug
     * @param UpdateBugRequest $request
     * @param Project $project
     * @param Bug $bug
     * @return JsonResponse
     */
    public function update(UpdateBugRequest $request, Project $project, Bug $bug): JsonResponse
    {
        $bug->update($request->validated());

        $bugComment = $bug->bug_comments()->first();
        $bugComment->update($request->validated());

        BugCommentFileController::do_upload_files($request, $bugComment);

        return response()->json(["success" => true]);
    }


    /**
     * Destroy the bug
     * @param Request $request
     * @param Project $project
     * @param Bug $bug
     * @return RedirectResponse
     */
    public function destroy(Request $request, Project $project, Bug $bug): RedirectResponse
    {
        $bug->delete();
        $flash_notification = [
            "title" => __('flash_bugtrackly.bug_deleted_title'),
            "text"  => __('flash_bugtrackly.bug_deleted_desc', ['bug_id' => $bug->id]),
        ];
        return to_route('projects.show', $project)->with('success', $flash_notification);
    }

    /**
     * Update du statut du bug
     * @param UpdateBugStatusRequest $request
     * @param Project $project
     * @param Bug $bug
     * @return JsonResponse
     */
    public function update_status(UpdateBugStatusRequest $request, Project $project, Bug $bug): JsonResponse
    {
        $bug->update($request->validated());
        return response()->json(["success" => true]);
    }

    /**
     * Update de la priorité du bug
     * @param UpdateBugPriorityRequest $request
     * @param Project $project
     * @param Bug $bug
     * @return JsonResponse
     */
    public function update_priority(UpdateBugPriorityRequest $request, Project $project, Bug $bug): JsonResponse
    {
        $bug->update($request->validated());
        return response()->json(["success" => true]);
    }

    /**
     * Toggle follow bug
     * @param Request $request
     * @param Project $project
     * @param Bug $bug
     * @return RedirectResponse
     */
    public function toggleFollowBug(Request $request, Project $project, Bug $bug): RedirectResponse
    {
        $request->validate([
            'followBug' => ['required', 'boolean']
        ]);

        if($request->get('followBug')) {
            $flash_notification = [
                "title" =>  __('flash_bugtrackly.bug_followed_title'),
                "text"  => __('flash_bugtrackly.bug_followed_desc'),
            ];
            $bug->user_followers()->attach(auth()->id());
            return to_route('projects.bug.show', [$project, $bug])->with('success', $flash_notification);
        }else{
            $flash_notification = [
                "title" =>  __('flash_bugtrackly.bug_unfollowed_title'),
                "text"  => __('flash_bugtrackly.bug_unfollowed_desc'),
            ];
            $bug->user_followers()->detach(auth()->id());
            return to_route('projects.bug.show', [$project, $bug])->with('warning', $flash_notification);
        }


    }
}
