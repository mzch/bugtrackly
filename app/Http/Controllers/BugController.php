<?php

namespace App\Http\Controllers;

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
use App\Trait\BugLog\HasBugLogMethods;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Response;

class BugController extends Controller
{
    use HasBugLogMethods;
    public function __construct(
        protected BugInfosRepositoryInterface $bug_infos_repository,
    )
    {
    }

    public function create(Project $project): Response
    {
        $project->load('users');
        $this->addBreadcrumb($project->name, route('projects.show', $project));
        $this->addBreadcrumb("Création d'un bug", false);
        $data = [
            'project'        => $project,
            'bug_priorities' => $this->bug_infos_repository->getAllBugPriorities()
        ];

        return $this->render('Bug/BugCreate', $data);
    }

    public function store(StoreBugRequest $request , Project $project): RedirectResponse
    {
        // Nouveau bug
        $bug = new Bug($request->validated());

        // Nouveau commentaire (description du bug)
        $bugComment = new BugComment($request->validated());

        // Enregistrement du bug et de son premier commentaire
        $project->bugs()->save($bug);
        $bug->bug_comments()->save($bugComment);

        // Log de l'action dans l'historique du bug
        self::logAction(
            $bug->id,
            auth()->id(),
            "Création d'un nouveau bug",
        );

        // log de la priorité
        $new_priority = $this->bug_infos_repository->getBugPriorityById($bug->priority);
        self::logAction(
            $bug->id,
            auth()->id(),
            "Priorité",
            " => " . $new_priority['label']
        );

        // log de l'utilisateur assigné si renseigné
        $assignedUser = false;
        if($request->validated('assigned_user_id')){
            $assignedUser = User::where('id', $request->validated('assigned_user_id'))->first();
            self::logAction(
                $bug->id,
                auth()->id(),
                "Assigné à",
                "=> " . $assignedUser->full_name,
            );
        }


        // email notif
        $usersToNotify = $project->users->where('role_id', 1); // les admins sur le projets
        if($assignedUser){
            $usersToNotify->push($assignedUser);
        }
        $usersToNotify = $usersToNotify->unique('id');
        foreach ($usersToNotify as $user) {
            $status = $this->bug_infos_repository->getBugStatusById($bug->status);
            $priority = $this->bug_infos_repository->getBugPriorityById($bug->priority);
            $user->notify(new BugCreatedNotification($project, $bug, $bugComment, $status, $priority));
        }


        return to_route('projects.show', $project);
    }

    public function show(Project $project, Bug $bug): Response
    {
        $project->load('users');
        $bug->load(['bug_comments', 'bug_logs']);
        $this->addBreadcrumb($project->name, route('projects.show', $project));
        $this->addBreadcrumb('Bug n°'.$bug->bug_id_formatted, route('projects.show', $project));
        $data = [
            'project'        => $project,
            'bug'        => $bug,
            'bug_status'     => $this->bug_infos_repository->getAllBugStatus(),
            'bug_priorities' => $this->bug_infos_repository->getAllBugPriorities()
        ];
        return $this->render('Bug/BugShow', $data);
    }

    public function update(UpdateBugRequest $request, Project $project, Bug $bug): JsonResponse{
        $bug->update($request->validated());
        $bug->bug_comments()->first()->update($request->validated());
        return response()->json(["success" => true]);
    }

    public function destroy(Request $request, Project $project, Bug $bug): JsonResponse
    {
        $bug->delete();
        return response()->json(["success" => true]);
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
}
