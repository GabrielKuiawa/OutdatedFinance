<?php

use App\Controllers\HomeController;
use Core\Router\Route;

// Authentication
Route::get('/', [HomeController::class, 'getAllUsers'])->name('root');
