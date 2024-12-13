<?php

namespace App\Http\Controllers\Settings;

use App\Http\Requests\Settings\Users\BackToUserAdminRequest;
use App\Http\Requests\Settings\Users\DeleteUserRequest;
use App\Http\Requests\Settings\Users\SwitchUserRequest;
use App\Models\User;
use App\Repositories\RolesPersmissions\RolesPersmissionsRepositoryInterface;
use App\Repositories\Users\UserRepositoryInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        $data = [
            'roles' => $this->rolesPermissionsRepository->getAllRoles(),
        ];
        return $this->render('Settings/Users/UserCreate', $data);
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

    /**
     * @param SwitchUserRequest $request
     * @param User $newUser
     * @return RedirectResponse
     */
    public function switchUser(SwitchUserRequest $request, User $newUser): RedirectResponse
    {
        $adminUserId = auth()->user()->id;
        session()->flush();
        Auth::login($newUser);
        session()->put('admin_user_id', $adminUserId);
        //return redirect()->route('admin.dashboard');
        return to_route('dashboard');
    }

    /**
     * @param BackToUserAdminRequest $request
     * @return RedirectResponse
     */
    public function backToAdminUser(BackToUserAdminRequest $request): RedirectResponse
    {
        $adminUserId = $request->session()->get('admin_user_id', false);
        if($adminUserId !== false) {
            $newUser = User::find($adminUserId);
            session()->flush();
            Auth::login($newUser);
            return to_route('settings.users.index');
        }
        return redirect()->back();
    }
}
