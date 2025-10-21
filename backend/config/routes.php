<?php

use App\Controllers\UsersController;
use App\Controllers\ExpensesController;
use Core\Router\Route;

Route::post('/api/login', [UsersController::class, 'login'])->name('api.login');
Route::get('/', [UsersController::class, 'getAllUsers'])->name('root');

Route::get('/{email}', [UsersController::class, 'getUserByEmail'])->name('root');

Route::middleware('auth')->group(function () {
    Route::get('/api/users', [UsersController::class, 'getUsers'])->name('api.users');

    Route::get('/api/expenses', [ExpensesController::class, 'index']);
    Route::get('/api/expenses/{id}', [ExpensesController::class, 'show']);
    Route::post('/api/expenses', [ExpensesController::class, 'create']);
    Route::put('/api/expenses/{id}', [ExpensesController::class, 'update']);
    Route::delete('/api/expenses/{id}', [ExpensesController::class, 'destroy']);
});

Route::middleware('admin')->group(function () {
    Route::get('/api/admin', [UsersController::class, 'getAdmins'])->name('api.admin');
});
