<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::post('/login', 'App\Http\Controllers\Api\UserController@login');
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/logout', [UserController::class, 'logout']);
    Route::prefix('user')->group(function () {
        Route::post('/search', [UserController::class, 'search'])->name('user.search');
        Route::get('/profile', [UserController::class, 'profile'])->name('user.profile');
        Route::put('/changePassword/{userId}', [UserController::class, 'changePassword'])->name('user.changePassword');
        Route::put('/changePasswordProfile', [UserController::class, 'changePasswordProfile'])->name('user.changePasswordProfile');
        Route::post('/resetPassword', [UserController::class, 'sendMail'])->name('user.sendMailResetPassword');
        Route::put('/resetPassword/{token}', [UserController::class, 'resetPassword'])->name('user.resetPassword');
    });
    Route::apiResource('user', UserController::class);
});

