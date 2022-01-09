<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\StatusLikeController;
use App\Http\Controllers\CommentLikesController;
use App\Http\Controllers\StatusCommentsController;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;
use App\Http\Controllers\Auth\AuthenticatedSessionController as Auth;
use App\Http\Controllers\Auth\RegisterController;

Route::get('/', function () {
    return view('welcome');
});


Route::post('/statuses', [StatusController::class, 'store'])
    ->name('statuses.store')->middleware('auth');

Route::get('/statuses', [StatusController::class, 'index'])
    ->name('statuses.index');

// route for likes \\
Route::post('/statuses/{status}/likes', [StatusLikeController::class, 'store'])
    ->name('statuses.like.store')->middleware('auth');

Route::delete('/statuses/{status}/likes', [StatusLikeController::class, 'destroy'])
    ->name('statuses.like.destroy')->middleware('auth');
// route for likes \\

// route for comments \\
Route::post('/statuses/{status}/comments', [StatusCommentsController::class, 'store'])
    ->name('statuses.comment.store')->middleware('auth');
// route for comments \\

// route for like comments \\

Route::post('/comments/{comment}/likes', [CommentLikesController::class, 'store'])
    ->name('comments.like.store')->middleware('auth');

Route::delete('/comments/{comment}/likes', [CommentLikesController::class, 'destroy'])
    ->name('comments.like.destroy')->middleware('auth');
// route for like comments \\

// route for Users \\
Route::get('@{user}', [UserController::class, 'show'])->name('users.show');
// route for Users \\




/// login \\\
Route::get('/login', [Auth::class, 'create'])->middleware('guest')->name('login');
Route::post('login', [AuthenticatedSessionController::class, 'store'])->name('login')
    ->middleware('guest');
Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

Route::get('/register', [RegisterController::class, 'create'])->middleware('guest')->name('register');
/// login \\\
