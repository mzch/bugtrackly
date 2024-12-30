<?php

namespace App\Providers;

use App\Repositories\BugStatus\BugStatusRepository;
use App\Repositories\BugStatus\BugStatusRepositoryInterface;
use App\Repositories\Projects\ProjectRepository;
use App\Repositories\Projects\ProjectRepositoryInterface;
use App\Repositories\RolesPersmissions\RolesPermissionsRepository;
use App\Repositories\RolesPersmissions\RolesPersmissionsRepositoryInterface;
use App\Repositories\Users\UserRepository;
use App\Repositories\Users\UserRepositoryInterface;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(RolesPersmissionsRepositoryInterface::class, RolesPermissionsRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(ProjectRepositoryInterface::class, ProjectRepository::class);
        $this->app->bind(BugStatusRepositoryInterface::class, BugStatusRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Vite::prefetch(concurrency: 3);
    }
}
