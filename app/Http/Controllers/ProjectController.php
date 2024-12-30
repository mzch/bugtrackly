<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Repositories\BugStatus\BugStatusRepositoryInterface;
use App\Repositories\Projects\ProjectRepositoryInterface;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function __construct(
        protected BugStatusRepositoryInterface $bug_status_repository
    )
    {
    }
    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        $this->addBreadcrumb($project->name, false);
        $data =[
            'project' => $project,
            'bug_status' => $this->bug_status_repository->getAllBugStatus()
        ];
        return $this->render('SingleProject/SingleProjectIndex', $data);
    }
}
