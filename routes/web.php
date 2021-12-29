<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\StatusController;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;
use App\Http\Controllers\Auth\AuthenticatedSessionController as Auth;

Route::get('/', function () {
    return view('welcome');
});


Route::post('/statuses', [StatusController::class, 'store'])
    ->name('statuses.store')
    ->middleware('auth');

Route::get('/statuses', [StatusController::class, 'index'])
    ->name('statuses.index');
//    ->middleware('auth');

Route::get('/login', [Auth::class, 'create'])->middleware('guest')->name('login');

Route::post('login', [AuthenticatedSessionController::class, 'store'])->name('login')
    ->middleware('guest');
Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
