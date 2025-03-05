<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Repositories\BugInfos\BugInfosRepositoryInterface;
use App\Repositories\Bugs\BugRepositoryInterface;
use App\Rules\ValidCategory;
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
            'field'     => 'in:id,title,date,priority,status,category',
            'priority'  => 'in:none,low,normal,hight,immediate',
            'status'    => 'in:all,new,accepted,rejected,in_progress,resolved,closed,reopened',
            'category'    => ['nullable', new ValidCategory()],
        ]);

        $this->addBreadcrumb($project->name, false);
        $project->load(['ticket_categories']);
        $project->ticket_categories->makeHidden(['created_at', 'updated_at', 'project_id']);
        $bugs = $this->bug_repository->getAllBugsPaginatedForProject($project, $request, 20);
        $data = [
            'project'        => $project,
            'bugs'           => $bugs,
            'filters'        => [
                'direction' => $request->get('direction', 'desc'),
                'field'     => $request->get('field', 'date'),
                'priority'  => $request->get('priority', null),
                'category'  => $request->get('category', null),
                'status'    => $request->get('status', null),
                'search'    => $request->get('search', null),
            ],
            'bug_status'     => $this->bug_status_repository->getAllBugStatus(),
            'bug_priorities' => $this->bug_status_repository->getAllBugPriorities(),
        ];
        return $this->render('SingleProject/SingleProjectIndex', $data);
    }
}
