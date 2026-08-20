<?php

use App\Http\Controllers\Settings\AppearanceController;
use App\Http\Controllers\Settings\PasswordController;
use App\Http\Controllers\Settings\ProfileController as SettingsProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\RoleAndPermissionController;
use App\Http\Controllers\PermissionController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('settings/profile', [SettingsProfileController::class, 'edit'])->name('settings.profile.edit');
    Route::put('settings/profile', [SettingsProfileController::class, 'update'])->name('settings.profile.update');
    Route::delete('settings/profile', [SettingsProfileController::class, 'destroy'])->name('settings.profile.destroy');
    Route::get('settings/password', [PasswordController::class, 'edit'])->name('settings.password.edit');
    Route::put('settings/password', [PasswordController::class, 'update'])->name('settings.password.update');
    Route::get('settings/appearance', [AppearanceController::class, 'edit'])->name('settings.appearance.edit');
});

require __DIR__.'/auth.php';


Route::middleware(['auth'])->group(function () {
    
    // Core User Isolation Protected Grouping (Spatie Middleware Filters)
    Route::middleware(['permission:view any users'])->group(function () {
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
    });

    Route::middleware(['permission:create users'])->group(function () {
        Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
    });

    Route::middleware(['permission:update users'])->group(function () {
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::patch('/users/{user}', [UserController::class, 'update'])->name('users.update');
    });

    Route::middleware(['permission:delete users'])->group(function () {
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    });
});



Route::middleware(['auth'])->group(function () {
    // Post Boundaries Mapping
    Route::middleware(['permission:view any post'])->group(function () {
        Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
    });

    Route::middleware(['permission:create post'])->group(function () {
        Route::get('/posts/create', [PostController::class, 'create'])->name('posts.create');
        Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
    });

    Route::middleware(['permission:update post'])->group(function () {
        Route::get('/posts/{post}/edit', [PostController::class, 'edit'])->name('posts.edit');
        Route::patch('/posts/{post}', [PostController::class, 'update'])->name('posts.update');
    });

    Route::middleware(['permission:delete post'])->group(function () {
        Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');
    });
});


Route::middleware(['auth'])->group(function () {
    // Spatie Group Privilege Management Boundaries Mapping
    Route::middleware(['permission:view any roles'])->group(function () {
        Route::get('/roles', [RoleAndPermissionController::class, 'index'])->name('roles.index');
    });

    Route::middleware(['permission:create roles'])->group(function () {
        Route::get('/roles/create', [RoleAndPermissionController::class, 'create'])->name('roles.create');
        Route::post('/roles', [RoleAndPermissionController::class, 'store'])->name('roles.store');
    });

    Route::middleware(['permission:update roles'])->group(function () {
        Route::get('/roles/{role}/edit', [RoleAndPermissionController::class, 'edit'])->name('roles.edit');
        Route::put('/roles/{role}', [RoleAndPermissionController::class, 'update'])->name('roles.update');
    });

    Route::middleware(['permission:delete roles'])->group(function () {
        Route::delete('/roles/{role}', [RoleAndPermissionController::class, 'destroy'])->name('roles.destroy');
    });
});

Route::resource('permissions', PermissionController::class);

