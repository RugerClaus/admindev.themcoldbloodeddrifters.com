<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return auth()->check() ? redirect('/dashboard') : redirect('/login');
});


Route::get('/login', [AuthController::class, 'show_login'])->name('login');
Route::post('/authenticate', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class,'init']);
});
