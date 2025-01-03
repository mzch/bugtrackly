<?php

namespace App\Providers;

use App\Models\Bug;
use App\Models\BugComment;
use App\Models\Project;
use App\Models\User;
use App\Repositories\RolesPersmissions\RolesPersmissionsRepositoryInterface;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class PersmissionsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $rolesPermissionsRepository = app(RolesPersmissionsRepositoryInterface::class);
        $permissions = $rolesPermissionsRepository->getAllPermissions();
        $permissions->each(function ($permission, $key) {
            Gate::define($permission['internal_name'], function ($user) use ($permission) {
                return $user->hasPermissionTo($permission['internal_name']);
            });
        });

        /**
         * Détermine si un utilisateur peut consulter un projet
         */
        Gate::define('view-project', function (User $user, Project $project) {
            return $user->role_id === 1 || $project->users->pluck('id')->contains($user->id);
        });

        /**
         * Validité du bug relatif au projet
         */
        Gate::define('view-bug', function (User $user , Project $project, Bug $bug) {
            return $project->id === $bug->project_id;
        });

        /**
         * Validité du commentaire de bug relatif au bug
         */
        Gate::define('view-bug-comment', function (User $user , Bug $bug, BugComment $bugComment) {
            return $bug->id === $bugComment->bug_id;
        });
    }
}
