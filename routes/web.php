<?php

use App\Http\Controllers\Settings\ProjectController;
use App\Http\Controllers\Settings\ProjectsController;
use App\Http\Controllers\Settings\SettingsController;
use App\Http\Controllers\Settings\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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
                Route::get('/show/{project}', [ProjectController::class, 'show'])->name('show');
            });

    });





Route::get('/projects/sop', [ProjectsController::class, 'soprotocol'])->middleware(['auth', 'verified'])->name('projects-sop');
Route::get('/projects/lauraco', [ProjectsController::class, 'lauraco'])->middleware(['auth', 'verified'])->name('projects-loraco');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/avatar', [ProfileController::class, 'update_avatar'])->name('profile.update_avatar');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
