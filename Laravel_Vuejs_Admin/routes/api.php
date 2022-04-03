<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\CategoryTruckController;
use App\Http\Controllers\Api\TruckController;
use App\Http\Controllers\Api\ItemTypeController;
use App\Http\Controllers\Api\PostController;

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
Route::post('/reset-password', [UserController::class, 'sendMail'])->name('user.sendMailResetPassword');
Route::put('/reset-password/{token}', [UserController::class, 'resetPassword'])->name('user.resetPassword');
Route::middleware(['auth:sanctum'])->group(function () {
    //Admin
    Route::get('/logout', [UserController::class, 'logout']);
    Route::prefix('user')->group(function () {
        Route::post('/search', [UserController::class, 'search'])->name('user.search');
        Route::get('/profile', [UserController::class, 'profile'])->name('user.profile');
        Route::post('/updateProfile', [UserController::class, 'updateProfile'])->name('user.updateProfile');
        Route::put('/changePassword/{userId}', [UserController::class, 'changePassword'])->name('user.changePassword');
        Route::put('/changePasswordProfile', [UserController::class, 'changePasswordProfile'])->name('user.changePasswordProfile');
    });
    //Customer
    Route::prefix('customer')->group(function () {
        // Route::get('/getDistance', [CustomerController::class, 'getDistance'])->name('customer.getDistance');
        Route::post('/verifyPhone', 'App\Http\Controllers\Api\CustomerController@verifiedPhone');
        Route::post('/search/{customerId}', [CustomerController::class, 'search'])->name('customer.search');
        Route::put('/change-password/{customerId}', [CustomerController::class, 'changePassword'])->name('customer.changePassword');
    });
    //Truck
    Route::prefix('truck')->group(function () {
        Route::post('/search', [TruckController::class, 'search'])->name("truck.search");
        Route::get('/get-city-name', [TruckController::class, 'getCityName'])->name("truck.getCityName");
    });
    //Post
    Route::prefix('post')->group(function () {
        Route::post('/listPost/{isApprove}/{status}', [PostController::class, 'listPost'])->name("post.listPost");
        Route::post('/updatePost/{id}', [PostController::class, 'updatePost'])->name("post.updatePost");
        Route::get('/is-approve-post/{id}', [PostController::class, 'isApprovePost'])->name("post.updatePost");
        Route::post('/search-post', [PostController::class, 'searchPost'])->name("post.searchPost");
    });
    Route::apiResource('user', UserController::class);
    Route::apiResource('customer', CustomerController::class);
    Route::apiResource('categoryTruck', CategoryTruckController::class);
    Route::apiResource('itemType', ItemTypeController::class);
    Route::apiResource('truck', TruckController::class);
    Route::apiResource('post', PostController::class);
});

