<?php

use App\Controllers\ExpenseResourceController;
use App\Controllers\UsersController;
use App\Controllers\ExpensesController;
use App\Controllers\GroupController;
use Core\Router\Route;

Route::post('/api/login', [UsersController::class, 'login'])->name('api.login');
Route::get('/', [UsersController::class, 'getAllUsers'])->name('root');

Route::middleware('auth')->group(function () {
    Route::get('/api/users', [UsersController::class, 'getUsers'])->name('api.users');

    Route::get('/api/expenses', [ExpensesController::class, 'index']);
    Route::get('/api/expenses/page/{page}', [ExpensesController::class, 'index']);
    Route::get('/api/expenses/{id}', [ExpensesController::class, 'show']);
    Route::post('/api/expenses', [ExpensesController::class, 'create']);
    Route::put('/api/expenses/{id}', [ExpensesController::class, 'update']);
    Route::delete('/api/expenses/{id}', [ExpensesController::class, 'destroy']);

    Route::get('/api/expenses/{id}/files', [ExpenseResourceController::class, 'index']);
    Route::post('/api/expenses/{id}/files', [ExpenseResourceController::class, 'create']);
    Route::delete('/api/expenses/{id}/files/{file_id}', [ExpenseResourceController::class, 'destroy']);

    Route::get('/api/groups', [GroupController::class, 'index']);
    Route::get('/api/groups/{id}', [GroupController::class, 'show']);
    Route::post('/api/groups', [GroupController::class, 'create']);
    Route::put('/api/groups/{id}', [GroupController::class, 'update']);
    Route::delete('/api/groups/{id}', [GroupController::class, 'destroy']);
});

Route::middleware('admin')->group(function () {
    Route::get('/api/admin', [UsersController::class, 'getAdmins'])->name('api.admin');
});
