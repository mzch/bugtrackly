<?php

namespace App\Providers;

use App\Repositories\BugInfos\BugInfosRepository;
use App\Repositories\BugInfos\BugInfosRepositoryInterface;
use App\Repositories\Bugs\BugRepository;
use App\Repositories\Bugs\BugRepositoryInterface;
use App\Repositories\Locales\LocaleRepository;
use App\Repositories\Locales\LocaleRepositoryInterface;
use App\Repositories\Projects\ProjectRepository;
use App\Repositories\Projects\ProjectRepositoryInterface;
use App\Repositories\RolesPersmissions\RolesPermissionsRepository;
use App\Repositories\RolesPersmissions\RolesPersmissionsRepositoryInterface;
use App\Repositories\TicketCategory\TicketCategoriesRepository;
use App\Repositories\TicketCategory\TicketCategoriesRepositoryInterface;
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
        $this->app->bind(BugInfosRepositoryInterface::class, BugInfosRepository::class);
        $this->app->bind(BugRepositoryInterface::class, BugRepository::class);
        $this->app->bind(LocaleRepositoryInterface::class, LocaleRepository::class);
        $this->app->bind(TicketCategoriesRepositoryInterface::class, TicketCategoriesRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Vite::prefetch(concurrency: 3);
    }
}
