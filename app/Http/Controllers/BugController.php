<?php

namespace App\Http\Controllers;

use App\Http\Requests\Bug\ShowBugRequest;
use App\Http\Requests\Bug\StoreBugRequest;
use App\Http\Requests\Bug\UpdateBugPriorityRequest;
use App\Http\Requests\Bug\UpdateBugRequest;
use App\Http\Requests\Bug\UpdateBugStatusRequest;
use App\Models\Bug;
use App\Models\BugComment;
use App\Models\Project;
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

    public function create(Project $project): Response
    {
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

        $bug = new Bug($request->validated());
        $bugComment = new BugComment($request->validated());

        $project->bugs()->save($bug);
        $bug->bug_comments()->save($bugComment);

        return to_route('projects.show', $project);
    }

    public function show(ShowBugRequest $request, Project $project, Bug $bug): Response
    {
        $bug->load('bug_comments');
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
