<?php

namespace App\Http\Controllers\Settings;

use Illuminate\Http\Request;
class ProjectsController extends SettingsController
{
    public function __construct(){
        parent::__construct();
        $this->addBreadcrumb('Gestion des projets', route('settings.projects.index'));
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
