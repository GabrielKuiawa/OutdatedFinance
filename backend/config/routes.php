<?php

use App\Controllers\UsersController;
use Core\Router\Route;

Route::post('/api/login', [UsersController::class, 'login'])->name('api.login');
Route::get('/', [UsersController::class, 'getAllUsers'])->name('root');
Route::get('/{email}', [UsersController::class, 'getUserByEmail'])->name('root');

Route::middleware('auth')->group(function () {
    Route::get('/api/users', [UsersController::class, 'getUserByEmail'])->name('api.users');
    
    Route::middleware('admin')->group(function () {
        Route::get('/api/admin', [UsersController::class, 'getUserByEmailAdmin'])->name('api.admin');
    });
});
