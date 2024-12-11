<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(){
        $this->addBreadcrumb('Accueil', route('dashboard'));
    }
    //
    public function index(){

        return $this->render('Dashboard', []);
    }
}
