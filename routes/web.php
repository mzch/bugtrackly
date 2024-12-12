<?php

use App\Http\Controllers\Admin\ProjectsController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::prefix('settings')
    ->name('settings.')
    ->group(function () {
        Route::get('/', [SettingsController::class, 'index'])->name('index');
        Route::get('users', [UsersController::class, 'index'])->name('users.index');
        Route::get('projects', [ProjectsController::class, 'index'])->name('projects.index');
    });





Route::get('/projects/sop', [ProjectsController::class, 'soprotocol'])->middleware(['auth', 'verified'])->name('projects-sop');
Route::get('/projects/lauraco', [ProjectsController::class, 'lauraco'])->middleware(['auth', 'verified'])->name('projects-loraco');

Route::get('/tpl', function () {
    return Inertia::render('Tpl/Index');
})->middleware(['auth', 'verified'])->name('tpl');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
