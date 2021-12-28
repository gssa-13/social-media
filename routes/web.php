<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\StatusController;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;

Route::get('/', function () {
    return view('welcome');
});


Route::post('statuses', [StatusController::class, 'store'])->name('statuses.store')->middleware('auth');

Route::post('login', [AuthenticatedSessionController::class, 'store'])->name('login');
Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
