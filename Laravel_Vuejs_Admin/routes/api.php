<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\CategoryTruckController;
use App\Http\Controllers\Api\TruckController;
use App\Http\Controllers\Api\ItemTypeController;

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
Route::post('/resetPassword', [UserController::class, 'sendMail'])->name('user.sendMailResetPassword');
Route::put('/resetPassword/{token}', [UserController::class, 'resetPassword'])->name('user.resetPassword');
Route::middleware(['auth:sanctum'])->group(function () {
    //Admin
    Route::get('/logout', [UserController::class, 'logout']);
    Route::prefix('user')->group(function () {
        Route::post('/search', [UserController::class, 'search'])->name('user.search');
        Route::get('/profile', [UserController::class, 'profile'])->name('user.profile');
        Route::put('/changePassword/{userId}', [UserController::class, 'changePassword'])->name('user.changePassword');
        Route::put('/changePasswordProfile', [UserController::class, 'changePasswordProfile'])->name('user.changePasswordProfile');
    });
    //Customer
    Route::prefix('customer')->group(function () {
        // Route::get('/getDistance', [CustomerController::class, 'getDistance'])->name('customer.getDistance');
        Route::post('/verifyPhone', 'App\Http\Controllers\Api\CustomerController@verifiedPhone');
        Route::post('/search/{customerId}', [CustomerController::class, 'search'])->name('customer.search');
        Route::put('/changePassword/{customerId}', [CustomerController::class, 'changePassword'])->name('customer.changePassword');
    });
    Route::apiResource('user', UserController::class);
    Route::apiResource('customer', CustomerController::class);
    Route::apiResource('categoryTruck', CategoryTruckController::class);
    Route::apiResource('itemType', ItemTypeController::class);
    Route::apiResource('truck', TruckController::class);
});

