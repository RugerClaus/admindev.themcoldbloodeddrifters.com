<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\BandMembersController;
use App\Http\Controllers\BandBioController;
use \App\Http\Controllers\CarouselController;
use \App\Http\Controllers\HomeController;

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
    Route::get('/messages/load_messages', [MessageController::class, 'load_messages']);

    Route::get('/messages/unread_count', [MessageController::class, 'get_unread_count']);


    Route::post('/users/add', [UsersController::class, 'add']);
    Route::post('/users/update', [UsersController::class, 'update']);
    Route::post('/users/delete', [UsersController::class, 'delete']);
    Route::post('/users/get_user_info', [UsersController::class, 'read']);
    Route::post('/users/must_change_password', [UsersController::class, 'must_change_password']);
    Route::post('/users/user_change_password', [UsersController::class, 'user_change_password']);

    Route::get('/carousel/list', [CarouselController::class, 'list']);
    Route::get('/carousel/read/{id}', [CarouselController::class, 'read']);
    Route::post('/carousel/create', [CarouselController::class, 'create']);
    Route::post('/carousel/update', [CarouselController::class, 'update']);
    Route::post('/carousel/delete', [CarouselController::class, 'delete']);

    Route::post('/home/update_left', [HomeController::class, 'update_left']);
    Route::post('/home/update_right', [HomeController::class, 'update_right']);


    Route::post('/band/bio/update', [BandBioController::class, 'update']);
    Route::post('/band/bio/delete_image', [BandBioController::class, 'delete_image']);

    Route::post('/band_members/bio/update', [BandMembersController::class, 'update']);
    Route::post('/band_members/delete_portrait', [BandMembersController::class, 'delete_portrait']);

    Route::post('/messages/mark_message_as_read', [MessageController::class, 'mark_message_as_read']);

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
