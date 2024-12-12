<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class ProjectsController extends SettingsController
{
    public function __construct(){
        parent::__construct();
        $this->addBreadcrumb('Gestion des projets', route('settings.projects.index'));
    }

    public function index(){
        return $this->render('Settings/Projects/ProjectsIndex', []);
    }

    //
    public function lauraco(){
        $this->addBreadcrumb('Lauraco', false);
        return $this->render('Settings/Projects/Lauraco', []);
    }
    public function soprotocol(){
        $this->addBreadcrumb('SoProtocol', false);
        return $this->render('Settings/Projects/SoProtocol', []);
    }
}
