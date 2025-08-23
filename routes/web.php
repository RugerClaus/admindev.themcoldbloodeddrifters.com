<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UsersController;

Route::get('/', function () {
    if (!auth()->check()) {
        return redirect('/login');
    }

    if (auth()->user()->must_change_password) {
        return redirect('/users/change_password');
    }

    return redirect('/dashboard');
});


Route::get('/login', [AuthController::class, 'show_login'])->name('login');
Route::post('/authenticate', [AuthController::class, 'login']);


Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class,'init'])->name('dashboard');
    Route::get('/users/change_password', [DashboardController::class, 'change_password'])->name('must.change.password');

    Route::post('/users/add', [UsersController::class, 'add']);
    Route::post('/users/update', [UsersController::class, 'update']);
    Route::post('/users/delete', [UsersController::class, 'delete']);
    Route::post('/users/get_user_info', [UsersController::class, 'read']);
    Route::post('/users/must_change_password', [UsersController::class, 'must_change_password']);
    Route::post('/users/user_change_password', [UsersController::class, 'user_change_password']);
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
