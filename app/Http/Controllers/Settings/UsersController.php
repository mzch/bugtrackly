<?php

namespace App\Http\Controllers\Settings;

use App\Http\Requests\Settings\Users\DeleteUserRequest;
use App\Models\User;
use App\Repositories\RolesPersmissions\RolesPersmissionsRepositoryInterface;
use App\Repositories\Users\UserRepositoryInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Response;

class UsersController extends SettingsController
{
    public function __construct(
        protected RolesPersmissionsRepositoryInterface $rolesPermissionsRepository,
        protected UserRepositoryInterface              $usersRepository)
    {
        parent::__construct();
        $this->addBreadcrumb('Utilisateurs', route('settings.users.index'));
    }

    /**
     * Displaying the list of users
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        $request->validate([
            'direction' => 'in:asc,desc',
            'field'     => 'in:name,email,role',
        ]);

        $data = [
            'users'   => $this->usersRepository->getAll($request),
            'filters' => $request->all(['search', 'field', 'direction']),
        ];
        return $this->render('Settings/Users/UsersIndex', $data);
    }

    public function create(): Response
    {
        $this->addBreadcrumb('Création', route('settings.users.create'));
        return $this->render('Settings/Users/UserCreate', []);
    }

    /**
     * Show the edit page for the user
     * @param Request $request
     * @param User $user
     * @return Response
     */
    public function show(Request $request, User $user): Response
    {
        $this->addBreadcrumb('Édition', route('settings.users.create'));
        $data = [
            'user' => $user,
        ];
        return $this->render('Settings/Users/UserShow', $data);
    }

    /**
     * Suppression d'un utilisateur
     * @param DeleteUserRequest $request
     * @param User $user
     * @return RedirectResponse
     */
    public function destroy(DeleteUserRequest $request, User $user)
    {
        $user->delete();
        $flashSuccess = [
            "title" => "Utilisateur supprimé",
            "text"  => $user->full_name . " a bien été supprimé."
        ];
        return to_route('settings.users.index')->with('success', $flashSuccess);
    }
}
