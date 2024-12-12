<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UsersController extends SettingsController
{
    public function __construct(){
        parent::__construct();
        $this->addBreadcrumb('Gestion des utilisateurs', route('settings.users.index'));
    }

    public function index()
    {
        return $this->render('Settings/Users/UsersIndex', []);
    }
}
