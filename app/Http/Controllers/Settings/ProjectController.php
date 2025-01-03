<?php

namespace App\Http\Controllers\Settings;

use App\Http\Requests\Settings\Projects\DeleteProjectRequest;
use App\Http\Requests\Settings\Projects\StoreProjectRequest;
use App\Http\Requests\Settings\Projects\UpdateProjectRequest;
use App\Models\Project;
use App\Models\User;
use App\Repositories\Projects\ProjectRepositoryInterface;
use App\Repositories\Users\UserRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Response;
use function MongoDB\BSON\toJSON;

class ProjectController extends SettingsController
{
    public function __construct(
        protected ProjectRepositoryInterface $project_repository,
        protected UserRepositoryInterface    $user_repository
    )
    {
        parent::__construct();
        $this->addBreadcrumb('Gestion des projets', route('settings.projects.index'));
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     */
    public function index(Request $request): Response
    {
        $request->validate([
            'direction' => 'in:asc,desc',
            'field'     => 'in:name,date',
        ]);


        $data = [
            'projects' => $this->project_repository->getAllPaginate($request),
            'filters'  => [
                'search'    => $request->get('search', null),
                'field'     => $request->get('field', 'date'),
                'direction' => $request->get('direction', 'desc'),
            ],
        ];

        return $this->render('Settings/Projects/ProjectIndex', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->addBreadcrumb('Création', route('settings.projects.create'));
        return $this->render('Settings/Projects/ProjectCreate');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProjectRequest $request)
    {
        //
        $validated = $request->validated();
        $project = Project::create([
            'name'       => $validated['name'],
            'slug'       => $validated['slug'],
            'short_desc' => $validated['short_desc'],
        ]);

        if ($photo = $request->validated("photo")) {
            $project->updateProjectPhoto($photo);
        }
        return to_route('settings.projects.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        $project->load('users');
        $this->addBreadcrumb('Édition', route('settings.projects.create'));
        $data = [
            'project' => $project,
            'users'   => $this->user_repository->getAll()->makeHidden('role')

        ];

        return $this->render('Settings/Projects/ProjectShow', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProjectRequest $request, Project $project)
    {
        $validated = $request->validated();
        $dataUpdate = [
            'name'       => $validated['name'],
            'slug'       => $validated['slug'],
            'short_desc' => $validated['short_desc'],
        ];
        $project->update($dataUpdate);
        $project->users()->sync($validated['users']);
        if ($request->validated("photo")) {
            $project->updateProjectPhoto($request->validated("photo"));
        }
        if ($request->validated("delete_old_photo")) {
            $project->deleteProjectPhoto();
        }

        return to_route('settings.projects.show', ['project' => $project]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DeleteProjectRequest $request, Project $project)
    {
        $project->delete();
        return to_route('settings.projects.index')->with('success');
    }

    public function create_slug(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string',
        ]);
        $originalSlug = \Str::slug($validated['name']);
        $slug = $originalSlug;
        $counter = 2;
        // Vérifie si le slug existe déjà pour un autre projet
        while (Project::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }
        return response()->json(["safeSlug" => $slug]);
    }


    public function validate_slug(Request $request, Project $project): JsonResponse
    {
        $validated = $request->validate([
            'slug' => 'nullable|string',
            'name' => 'nullable|string',
        ]);
        if ($validated['slug'] === null) {
            if ($validated['name'] === null) {
                $name = $project->name;
            } else {
                $name = $validated['name'];
            }
            $validated['slug'] = \Str::slug($name);
        }
        $originalSlug = \Str::slug($validated['slug']);
        $slug = $originalSlug;
        $counter = 2;
        // Vérifie si le slug existe déjà pour un autre projet
        while (Project::where('slug', $slug)->where('id', '!=', $project->id)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }
        return response()->json(["safeSlug" => $slug]);
    }
}
