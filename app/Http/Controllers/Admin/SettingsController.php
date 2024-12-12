<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function __construct(){
        $this->addBreadcrumb('Paramètres', route('settings.index'));
    }

    public function index()
    {
        return $this->render('Settings/SettingsIndex', []);
    }
}
