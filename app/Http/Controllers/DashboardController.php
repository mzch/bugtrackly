<?php

namespace App\Http\Controllers;

use App\Repositories\Projects\ProjectRepositoryInterface;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(
        protected ProjectRepositoryInterface $project_repository
    )
    {

        $this->addBreadcrumb('Gestion des projets', route('settings.projects.index'));
    }
    public function index(Request $request){
        $data = [
            'projects' => $this->project_repository->getProjectsForCurrentUser($request),
        ];
        return $this->render('Dashboard', $data);
    }
}
