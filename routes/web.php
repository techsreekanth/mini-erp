<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    ProjectsController,
    ResourceAllocationController,
    ResourceController,
    UsersController
};

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


    Route::resource('users', UsersController::class);
    Route::resource('projects', ProjectsController::class);
    Route::resource('resources', ResourceController::class);


    Route::post('/allocate-resource', [ResourceAllocationController::class, 'allocateResource']);
    Route::delete('/deallocate-resource/{id}', [ResourceAllocationController::class, 'deallocateResource']);
    Route::get('/projects/{id}/resources', [ResourceAllocationController::class, 'showProjectResources']);
});

require __DIR__ . '/auth.php';
