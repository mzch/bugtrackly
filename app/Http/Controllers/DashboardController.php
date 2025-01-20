<?php

namespace App\Http\Controllers;

use App\Repositories\BugInfos\BugInfosRepositoryInterface;
use App\Repositories\Bugs\BugRepositoryInterface;
use App\Repositories\Projects\ProjectRepositoryInterface;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(
        protected ProjectRepositoryInterface $project_repository,
        protected BugRepositoryInterface $bug_repository,
        protected BugInfosRepositoryInterface $bug_status_repository,
    )
    {

        $this->addBreadcrumb('Gestion des projets', route('settings.projects.index'));
    }
    public function index(Request $request){
        $projects = $this->project_repository->getProjectsForCurrentUser($request);
        $project_slug = $projects->pluck('slug')->toArray();
//        dump($request->get('priority'));
        $request->validate([
            'direction' => 'in:asc,desc',
            'field'     => 'in:id,title,date,priority,status,project',
            'priority'  => 'in:none,low,normal,hight,immediate',
            'project'   => 'in:' . implode(',', $project_slug),
            'status'    => 'in:all,new,accepted,rejected,in_progress,resolved,closed,reopened',
        ]);


        $data = [
            'projects' => $projects,
            'followed_bugs' => $this->bug_repository->getAllFollowedBugsPaginated($request),
            'bug_status'     => $this->bug_status_repository->getAllBugStatus(),
            'bug_priorities' => $this->bug_status_repository->getAllBugPriorities(),
            'filters'        => [
                'direction' => $request->get('direction', 'desc'),
                'field'     => $request->get('field', 'date'),
                'priority'  => $request->get('priority', null),
                'project'  => $request->get('project', null),
                'status'    => $request->get('status', null),
                'search'    => $request->get('search', null),
            ],
        ];
        return $this->render('Dashboard', $data);
    }
}
