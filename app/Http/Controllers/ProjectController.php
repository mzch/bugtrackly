<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Repositories\BugInfos\BugInfosRepositoryInterface;
use App\Repositories\Bugs\BugRepositoryInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Response;

class ProjectController extends Controller
{
    public function __construct(
        protected BugInfosRepositoryInterface $bug_status_repository,
        protected BugRepositoryInterface      $bug_repository,
    )
    {
    }

    public function index(Request $request):RedirectResponse{
        return to_route('projects.show', $request->user()->projects->first());
    }
    /**
     * Display the specified resource.
     */
    public function show(Request $request, Project $project): Response
    {
        $request->validate([
            'direction' => 'in:asc,desc',
            'field'     => 'in:id,title,date,priority',
            'priority'  => 'in:none,low,normal,hight,immediate',
            'status'    => 'in:all,new,accepted,rejected,in_progress,resolved,closed,reopened',
        ]);

        $this->addBreadcrumb($project->name, false);


        $data = [
            'project'        => $project,
            'bugs'           => $this->bug_repository->getAllBugsPaginatedForProject($project, $request, 20),
            'filters'        => [
                'direction' => $request->get('direction', 'desc'),
                'field'     => $request->get('field', 'date'),
                'priority'  => $request->get('priority', null),
                'status'    => $request->get('status', null),
                'search'    => $request->get('search', null),
            ],
            'bug_status'     => $this->bug_status_repository->getAllBugStatus(),
            'bug_priorities' => $this->bug_status_repository->getAllBugPriorities(),
        ];
        return $this->render('SingleProject/SingleProjectIndex', $data);
    }
}
