<?php

namespace App\Providers;

use App\Repositories\RolesPersmissions\RolesPermissionsRepository;
use App\Repositories\RolesPersmissions\RolesPersmissionsRepositoryInterface;
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
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Vite::prefetch(concurrency: 3);
    }
}
