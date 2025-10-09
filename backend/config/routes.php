<?php

use App\Controllers\UsersController;
use Core\Router\Route;

// Authentication
Route::get('/', [UsersController::class, 'getAllUsers'])->name('root');
