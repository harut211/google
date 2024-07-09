<?php

use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\CalendarController;
Route::get('/', [LoginController::class, 'index']);

Route::get('/redirect-google', [GoogleController::class,'redirect'])->name('redirect-google');
Route::get('/google-callback', [GoogleController::class,'callback'])->name('google-callback');

Route::middleware('auth')->group(function(){
    Route::get('/home',[GoogleController::class,'home'])->name('home');
    Route::get('/logout',[GoogleController::class,'logout'])->name('logout');
    Route::post('/addEvent',[CalendarController::class,'addEvent'])->name('calendar-event');
});
