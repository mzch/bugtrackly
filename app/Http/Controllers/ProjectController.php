<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Repositories\BugInfos\BugInfosRepositoryInterface;

class ProjectController extends Controller
{
    public function __construct(
        protected BugInfosRepositoryInterface $bug_status_repository
    )
    {
    }
    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        $project->load('bugs');
        $this->addBreadcrumb($project->name, false);
        $data =[
            'project' => $project,
            'bug_status' => $this->bug_status_repository->getAllBugStatus(),
            'bug_priorities' => $this->bug_status_repository->getAllBugPriorities(),
        ];
        return $this->render('SingleProject/SingleProjectIndex', $data);
    }
}
