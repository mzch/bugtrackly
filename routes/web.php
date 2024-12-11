<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectsController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/settings/users', function () {
    return Inertia::render('Settings/Users');
})->middleware(['auth', 'verified'])->name('users');
Route::get('/settings/projects', function () {
    return Inertia::render('Settings/Projects');
})->middleware(['auth', 'verified'])->name('settings-project');



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
