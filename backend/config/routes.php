<?php

use App\Controllers\UsersController;
use App\Controllers\ExpensesController;
use Core\Router\Route;

Route::post('/api/login', [UsersController::class, 'login'])->name('api.login');
Route::get('/', [UsersController::class, 'getAllUsers'])->name('root');

Route::get('/{email}', [UsersController::class, 'getUserByEmail'])->name('root');

Route::get('/api/expenses', [ExpensesController::class, 'index']);
Route::get('/api/expenses/{id}', [ExpensesController::class, 'show']);
Route::post('/api/expenses', [ExpensesController::class, 'store']);
Route::put('/api/expenses/{id}', [ExpensesController::class, 'update']);
Route::delete('/api/expenses/{id}', [ExpensesController::class, 'destroy']);

Route::middleware('auth')->group(function () {
    Route::get('/api/users', [UsersController::class, 'getUsers'])->name('api.users');


    // Route::get('/expenses', [\App\Controllers\ExpensesController::class, 'index']);
    // Route::get('/expenses/{id}', [\App\Controllers\ExpensesController::class, 'show']);
    // Route::post('/expenses', [\App\Controllers\ExpensesController::class, 'store']);
    // Route::put('/expenses/{id}', [\App\Controllers\ExpensesController::class, 'update']);
    // Route::delete('/expenses/{id}', [\App\Controllers\ExpensesController::class, 'destroy']);

});

Route::middleware('admin')->group(function () {
    Route::get('/api/admin', [UsersController::class, 'getAdmins'])->name('api.admin');
});
