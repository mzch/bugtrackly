<?php

namespace App\Http\Controllers\Settings;

use App\Http\Requests\Settings\Users\BackToUserAdminRequest;
use App\Http\Requests\Settings\Users\CreateUserRequest;
use App\Http\Requests\Settings\Users\DeleteUserRequest;
use App\Http\Requests\Settings\Users\SwitchUserRequest;
use App\Http\Requests\Settings\Users\UpdateUserRequest;
use App\Models\User;
use App\Notifications\Users\UserCreatedNotification;
use App\Notifications\Users\UserPasswordChangedNotification;
use App\Repositories\RolesPersmissions\RolesPersmissionsRepositoryInterface;
use App\Repositories\Users\UserRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Inertia\Response;

class UserController extends SettingsController
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
            'users'   => $this->usersRepository->getAllPaginate($request),
            'filters' => $request->all(['search', 'field', 'direction']),
        ];
        return $this->render('Settings/Users/UserIndex', $data);
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
     * Enregistre un nouvel utilisateur
     * @param CreateUserRequest $request
     * @return RedirectResponse
     */
    public function store(CreateUserRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $user = User::create([
            'first_name' => $validated['first_name'],
            'last_name'  => $validated['last_name'],
            'email'      => $validated['email'],
            'role_id'    => $validated['role_id'],
            'password'   => Hash::make($validated['password']),
            'email_verified_at' => Carbon::now(),
        ]);

        if($photo = $request->validated("photo")){
            $user->updateProfilePhoto($photo);
        }

        // Notifier l'utilisateur
        if($validated['send_password']) {
            $user->notify(new UserCreatedNotification($validated['password']));
        }

        $flash_notification = [
            "title" => "Utilisateur créé",
            "text" => "L'utilisateur <strong>" . e($user->full_name) . "</strong> a bien été créé."
        ];

        return to_route('settings.users.index')->with('success', $flash_notification);;

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
            'roles' => $this->rolesPermissionsRepository->getAllRoles(),
        ];
        return $this->render('Settings/Users/UserShow', $data);
    }

    public function update(UpdateUserRequest $request, User $user):RedirectResponse
    {
        $validated = $request->validated();
        $dataUpdate = [
            'first_name' => $validated['first_name'],
            'last_name'  => $validated['last_name'],
            'email'      => $validated['email'],
            'role_id'    => $validated['role_id'],
        ];
        if( $request->has('password') && $validated['password']!==null ) {
            $dataUpdate['password'] = Hash::make($validated['password']);
            if($validated['send_new_password']) {
                $user->notify(new UserPasswordChangedNotification($validated['password']));
            }

        }
        $user->update($dataUpdate);

        if($request->validated("photo")){
            $user->updateProfilePhoto($request->validated("photo"));
        }
        if($request->validated("delete_old_photo")){
            $user->deleteProfilePhoto();
        }

        $flash_notification = [
            "title" => "Utilisateur modifié",
            "text" => "L'utilisateur <strong>" . e($user->full_name) . "</strong> a bien été modifié."
        ];

        return to_route('settings.users.index')->with('success', $flash_notification);;;
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
            "text"  => "L'utilisateur <strong>" . e($user->full_name) . "</strong> a bien été supprimé."
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
        $flashSuccess = [
            "title" => "Connecté !",
            "text"  => "Vous êtes maintenant connecté en tant que <strong>" . e($newUser->full_name) . "</strong>."
        ];

        return to_route('dashboard')->with('warning', $flashSuccess);
    }
}
