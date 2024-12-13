<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Repositories\RolesPersmissions\RolesPersmissionsRepositoryInterface;
use App\Repositories\Users\UserRepositoryInterface;
use Illuminate\Http\Request;
use Inertia\Response;

class UsersController extends SettingsController
{
    public function __construct(
        protected RolesPersmissionsRepositoryInterface $rolesPermissionsRepository,
        protected UserRepositoryInterface $usersRepository){
        parent::__construct();
        $this->addBreadcrumb('Utilisateurs', route('settings.users.index'));
    }

    public function index(Request $request): Response
    {
        $request->validate([
            'direction' => 'in:asc,desc',
            'field'     => 'in:name,email,role',
        ]);

        $data = [
            'users'   => $this->usersRepository->getAll($request, 3),
            'filters' => $request->all(['search', 'field', 'direction']),
            //'roles'   => $this->rolesPermissionsRepository->getAllRolesWithPermissions(),
        ];
        return $this->render('Settings/Users/UsersIndex', $data);
    }
    public function create(): Response
    {
        $this->addBreadcrumb('Création', route('settings.users.create'));
        return $this->render('Settings/Users/UserCreate', []);
    }

    public function show(): Response
    {
        $this->addBreadcrumb('Édition', route('settings.users.create'));
        return $this->render('Settings/Users/UserShow', []);
    }
}
