<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index(Request $request){
        dump("index");
    }
    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        $this->addBreadcrumb($project->name, false);
        $data =[
            'project' => $project
        ];
        return $this->render('SingleProject/SingleProjectIndex', $data);
    }
}
