<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Repositories\BugInfos\BugInfosRepositoryInterface;
use App\Repositories\Bugs\BugRepositoryInterface;
use Illuminate\Http\Request;
use Inertia\Response;

class ProjectController extends Controller
{
    public function __construct(
        protected BugInfosRepositoryInterface $bug_status_repository,
        protected BugRepositoryInterface $bug_repository,
    )
    {
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Project $project): Response
    {
        $request->validate([
            'direction' => 'in:asc,desc',
            'field'     => 'in:name,email,role',
        ]);

        $this->addBreadcrumb($project->name, false);


        $data =[
            'project' => $project,
            'bugs' => $this->bug_repository->getAllBugsPaginatedForProject($project, $request, 20),
            'filters' => $request->all(['search', 'field', 'direction']),
            'bug_status' => $this->bug_status_repository->getAllBugStatus(),
            'bug_priorities' => $this->bug_status_repository->getAllBugPriorities(),
        ];
        return $this->render('SingleProject/SingleProjectIndex', $data);
    }
}
