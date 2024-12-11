<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProjectsController extends Controller
{
    public function __construct(){
        $this->addBreadcrumb('Accueil', route('dashboard'));
    }
    //
    public function lauraco(){
        $this->addBreadcrumb('Lauraco', false);
        return $this->render('Projects/Lauraco', []);
    }
    public function soprotocol(){
        $this->addBreadcrumb('SoProtocol', false);
        return $this->render('Projects/SoProtocol', []);
    }
}
