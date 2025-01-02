<?php

namespace App\Http\Controllers;

use App\Http\Requests\Bug\StoreBugRequest;
use App\Models\Bug;
use App\Models\Project;
use App\Repositories\BugInfos\BugInfosRepositoryInterface;
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
        $project->bugs()->save($bug);
        return to_route('projects.show', $project);
    }

    public function show(Project $project, Bug $bug): Response
    {
        //$bug->load('user');
        $this->addBreadcrumb($project->name, route('projects.show', $project));
        $this->addBreadcrumb('Bug n°'.$bug->bug_id_formatted, route('projects.show', $project));
        $data = [
            'project'        => $project,
            'bug'        => $bug,
            'bug_priorities' => $this->bug_infos_repository->getAllBugPriorities()
        ];
        return $this->render('Bug/BugShow', $data);
    }
}
