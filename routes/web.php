<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController as FrontProjectController;
use App\Http\Controllers\Settings\ProjectController;
use App\Http\Controllers\Settings\SettingsController;
use App\Http\Controllers\Settings\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::prefix('settings')
    ->name('settings.')
    ->middleware(['auth', 'verified', 'can:access-settings'])
    ->group(function () {
        Route::get('/', [SettingsController::class, 'index'])->name('index');
        Route::prefix('users')
            ->name('users.')
            ->middleware(['can:manage-users'])
            ->group(function () {
                Route::get('/', [UserController::class, 'index'])->name('index');
                Route::post('/', [UserController::class, 'store'])->name('store');
                Route::get('create', [UserController::class, 'create'])->name('create');
                Route::get('/show/{user}', [UserController::class, 'show'])->name('show')->where('user', '[0-9]+');
                Route::post('/show/{user}', [UserController::class, 'update'])->name('update')->where('user', '[0-9]+');
                Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy')->where('user', '[0-9]+');
                Route::get('switch-user/{newUser}', [UserController::class, 'switchUser'])->name('switch_user');
                Route::get('back-to-admin-user', [UserController::class, 'backToAdminUser'])->name('back_to_admin_user')->withoutMiddleware(['can:access-settings','can:manage-users']);
            });

        Route::prefix('projects')
            ->name('projects.')
            ->middleware(['can:manage-projects'])
            ->group(function () {
                Route::get('/', [ProjectController::class, 'index'])->name('index');
                Route::get('create', [ProjectController::class, 'create'])->name('create');
                Route::post('/', [ProjectController::class, 'store'])->name('store');
                Route::get('/show/{project}', [ProjectController::class, 'show'])->name('show');
                Route::post('/show/{project}', [ProjectController::class, 'update'])->name('update');
                Route::post('/show/{project}/validate_new_slug', [ProjectController::class, 'validate_slug'])->name('validate_slug');
                Route::post('/create_slug', [ProjectController::class, 'create_slug'])->name('create_slug');
            });

    });


Route::prefix('projets')
    ->name('projects.')
    ->group(function () {
        Route::get('/', [FrontProjectController::class, 'index'])->name('index');
        Route::get('/{project}', [FrontProjectController::class, 'show'])->name('show');
    });


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/avatar', [ProfileController::class, 'update_avatar'])->name('profile.update_avatar');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
