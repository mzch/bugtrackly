<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Response;

class SettingsController extends Controller
{
    public function __construct(){
        $this->addBreadcrumb('Paramètres', route('settings.index'));
    }

    public function index(Request $request): Response
    {
        return $this->render('Settings/SettingsIndex', []);
    }
}
