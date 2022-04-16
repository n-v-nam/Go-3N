<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\CategoryTruckController;
use App\Http\Controllers\Api\TruckController;
use App\Http\Controllers\Api\ItemTypeController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\Client\CustomerController as ClientCustomerController;
use App\Http\Controllers\Api\Client\DriverController;
use App\Http\Controllers\Api\PersonnelNotificationController;
use App\Http\Controllers\Api\Client\DriverPostController;
use App\Http\Controllers\Api\Client\CustomerBookTruckController;

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

//client
Route::post('/customer-register', 'App\Http\Controllers\Api\Client\CustomerController@register')->name("clientCustomer.register");
Route::post('/customer-active_account', 'App\Http\Controllers\Api\Client\CustomerController@activeAccount')->name("clientCustomer.activeAccount");
Route::post('/customer-login', 'App\Http\Controllers\Api\Client\CustomerController@login')->name("clientCustomer.login");
Route::post('client-customer/forget-password', [ClientCustomerController::class, 'forgetPassword'])->name('clientCustomer.forgetPassword');
Route::post('client-customer/new-password', [ClientCustomerController::class, 'newPassword'])->name('clientCustomer.newPassword');
Route::post('client-customer/verify-phone', [ClientCustomerController::class, 'verifiedPhone'])->name('clientCustomer.verifiedPhone');

Route::middleware(['auth:sanctum'])->group(function () {
    Route::middleware(['admin'])->group(function () {
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
            Route::get('/list-truck/{status}', [TruckController::class, 'listTruck'])->name("truck.listTruck");
            Route::post('/search', [TruckController::class, 'search'])->name("truck.search");
            Route::get('/get-city-name', [TruckController::class, 'getCityName'])->name("truck.getCityName");
            Route::get('/is-approve-truck/{id}', [TruckController::class, 'isApproveTruck'])->name("truck.isApproveTruck");
        });
        //Post
        Route::prefix('post')->group(function () {
            Route::post('/listPost/{isApprove}/{status}', [PostController::class, 'listPost'])->name("post.listPost");
            Route::post('/updatePost/{id}', [PostController::class, 'updatePost'])->name("post.updatePost");
            Route::get('/is-approve-post/{id}', [PostController::class, 'isApprovePost'])->name("post.isApprovePost");
            Route::post('/search-post', [PostController::class, 'searchPost'])->name("post.searchPost");
        });

        Route::apiResource('user', UserController::class);
        Route::apiResource('customer', CustomerController::class);
        Route::apiResource('truck', TruckController::class);
        Route::apiResource('post', PostController::class);
        Route::apiResource('personnel-notifications', PersonnelNotificationController::class);
    });
    //client

    Route::middleware(['guest'])->group(function () {
        Route::get('/customer-logout', [ClientCustomerController::class, 'logout']);
        Route::prefix('client-customer')->group(function () {
            Route::get('/profile', [ClientCustomerController::class, 'profile'])->name('clientCustomer.profile');
            Route::post('/update-profile', [ClientCustomerController::class, 'updateProfile'])->name('clientCustomer.updateProfile');
            Route::put('/change-password', [ClientCustomerController::class, 'changePassword'])->name('clientCustomer.changepassword');

        });
        //Driver
        Route::prefix('driver-post')->group(function () {
            Route::post('/list-post/{isApprove}/{status}', [DriverPostController::class, 'listPost'])->name('driver.listPost');
        });
        //
        Route::prefix('customer-book-truck')->group(function () {
            Route::post('/search-post', [CustomerBookTruckController::class, 'searchPost'])->name('customerBookTruck.search');
            Route::post('/book-truck/{postId}', [CustomerBookTruckController::class, 'bookTruck'])->name('customerBookTruck.bookTruck');
        });

        Route::apiResource('driver', DriverController::class);
        Route::apiResource('driver-post', DriverPostController::class);
        Route::apiResource('category-truck', CategoryTruckController::class);
        Route::apiResource('item-type', ItemTypeController::class);
    });
});




