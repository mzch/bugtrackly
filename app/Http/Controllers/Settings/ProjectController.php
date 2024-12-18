<?php

namespace App\Http\Controllers\Settings;

use App\Http\Requests\Settings\Projects\StoreProjectRequest;
use App\Http\Requests\Settings\Projects\UpdateProjectRequest;
use App\Models\Project;
use App\Repositories\Projects\ProjectRepositoryInterface;
use Illuminate\Http\Request;
use Inertia\Response;

class ProjectController extends SettingsController
{
    public function __construct(
        protected ProjectRepositoryInterface $project_repository
    ){
        parent::__construct();
        $this->addBreadcrumb('Gestion des projets', route('settings.projects.index'));
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     */
    public function index(Request $request):Response
    {
        $request->validate([
            'direction' => 'in:asc,desc',
            'field'     => 'in:name,email,role',
        ]);

        $data = [
            'projects' => $this->project_repository->getAll($request),
            'filters' => $request->all(['search', 'field', 'direction']),
        ];
        return $this->render('Settings/Projects/ProjectIndex', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return $this->render('Settings/Projects/ProjectCreate');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProjectRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        $data =[
            'project' => $project
        ];
        return $this->render('Settings/Projects/ProjectShow', $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProjectRequest $request, Project $project)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        //
    }
}
