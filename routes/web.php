<?php

use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GoogleController;
use App\Http\Middleware\AlreadyAuth;


Route::middleware(AlreadyAuth::class)->group(function () {

    Route::get('/', [LoginController::class, 'index'])->name('login');
    Route::get('/redirect-google', [GoogleController::class, 'redirect'])->name('redirect-google');
    Route::get('/google-callback', [GoogleController::class, 'callback'])->name('google-callback');

});

Route::middleware('auth')->group(function () {

    Route::get('/home', [GoogleController::class, 'home'])->name('home');
    Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::post('/addEvent', [GoogleController::class, 'addEvent'])->name('calendar-event');
    Route::get('/del-event', [GoogleController::class, 'delEvent']);

    Route::get('/edit-page', [GoogleController::class, 'editPage'])->name('edit-page');
    Route::post('/edit-event', [GoogleController::class, 'editEvent'])->name('edit-event');

});
