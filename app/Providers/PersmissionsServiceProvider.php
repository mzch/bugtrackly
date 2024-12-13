<?php

namespace App\Providers;

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
    }
}
