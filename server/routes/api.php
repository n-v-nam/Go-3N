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
use App\Http\Controllers\Api\CityController;
use App\Http\Controllers\Api\Client\CustomerNotificationController;
use App\Http\Controllers\Api\Client\PaymentController;
use App\Http\Controllers\Api\Client\ReportDriverController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\Client\FavoritePostController;

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
Route::apiResource('category-truck', CategoryTruckController::class);
Route::apiResource('item-type', ItemTypeController::class);

//client
Route::post('/customer-register', 'App\Http\Controllers\Api\Client\CustomerController@register')->name("clientCustomer.register");
Route::post('/customer-active-account', 'App\Http\Controllers\Api\Client\CustomerController@activeAccount')->name("clientCustomer.activeAccount");
Route::post('/customer-login', 'App\Http\Controllers\Api\Client\CustomerController@login')->name("clientCustomer.login");
Route::post('client-customer/forget-password', [ClientCustomerController::class, 'forgetPassword'])->name('clientCustomer.forgetPassword');
Route::post('client-customer/new-password', [ClientCustomerController::class, 'newPassword'])->name('clientCustomer.newPassword');
Route::post('client-customer/verify-phone', [ClientCustomerController::class, 'verifiedPhone'])->name('clientCustomer.verifiedPhone');
Route::get('client-customer/active-email/{token}', [ClientCustomerController::class, 'customerActiveMail'])->name('clientCustomer.active-email');
//admin+client
Route::get('/get-city', [CityController::class, 'getCity'])->name("city.getCity");
Route::get('/get-district/{cityId}', [CityController::class, 'getDistrict'])->name("city.getDistrict");

Route::middleware(['auth:sanctum'])->group(function () {
    Route::middleware(['admin'])->group(function () {
    //Admin
        Route::get('/logout', [UserController::class, 'logout']);
        Route::prefix('user')->group(function () {
            Route::post('/search', [UserController::class, 'search'])->name('user.search');
            Route::get('/profile', [UserController::class, 'profile'])->name('user.profile');
            Route::put('/update-profile', [UserController::class, 'updateProfile'])->name('user.updateProfile');
            Route::put('/change-password/{userId}', [UserController::class, 'changePassword'])->name('user.changePassword');
            Route::put('/change-password-profile', [UserController::class, 'changePasswordProfile'])->name('user.changePasswordProfile');
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
            Route::get('/is-approve-truck/{id}', [TruckController::class, 'isApproveTruck'])->name("truck.isApproveTruck");
        });
        //Post
        Route::prefix('post')->group(function () {
            Route::post('/list-post/{isApprove}/{status}', [PostController::class, 'listPost'])->name("post.listPost");
            Route::put('/update-post/{id}', [PostController::class, 'updatePost'])->name("post.updatePost");
            Route::get('/is-approve-post/{id}', [PostController::class, 'isApprovePost'])->name("post.isApprovePost");
            Route::post('/search-post', [PostController::class, 'searchPost'])->name("post.searchPost");
        });
        Route::post('/dashboard', [DashboardController::class, 'dashboard'])->name("dashboard.dashboard");
        Route::apiResource('user', UserController::class);
        Route::apiResource('customer', CustomerController::class);
        Route::apiResource('truck', TruckController::class);
        Route::apiResource('post', PostController::class);
        Route::apiResource('personnel-notifications', PersonnelNotificationController::class);
        Route::prefix('personnel-notifications')->group(function () {
            Route::get('/read-personnel-notifications/{id}', [PersonnelNotificationController::class, 'readPersonnelNotification'])->name("personnelNotifications.readPersonnelNotification");
        });
        Route::apiResource('order', OrderController::class);
    });
    //client

    Route::middleware(['guest'])->group(function () {
        Route::get('/customer-logout', [ClientCustomerController::class, 'logout']);
        Route::prefix('client-customer')->group(function () {
            Route::get('/profile', [ClientCustomerController::class, 'profile'])->name('clientCustomer.profile');
            Route::post('/update-profile', [ClientCustomerController::class, 'updateProfile'])->name('clientCustomer.updateProfile');
            Route::put('/change-password', [ClientCustomerController::class, 'changePassword'])->name('clientCustomer.changepassword');
            Route::post('/add-email', [ClientCustomerController::class, 'addMail'])->name('clientCustomer.addMail');
            Route::post('/save-bill', [PaymentController::class, 'saveBill'])->name("clientCustomer.saveBill");
        });

        //Route::middleware(['driver'])->group(function () {
            //Driver
            Route::prefix('driver-post')->group(function () {
                Route::post('/list-post/{isApprove}/{status}', [DriverPostController::class, 'listPost'])->name('driver-post.listPost');
                Route::get('/view-order/{OrderInformationId}', [DriverPostController::class, 'viewOrder'])->name("driver-post.viewOrder");
                Route::get('/accept-customer-book-order/{orderInformationId}', [DriverPostController::class, 'acceptCustomerBookOrder'])->name("driver-post.acceptCustomerBookOrder");
                Route::get('/driver-cancel-order/{orderInformationId}', [DriverPostController::class, 'driverCancelOrder'])->name("driver-post.driverCancelOrder");
                Route::get('/view-suggest/{suggestTruckId}', [DriverPostController::class, 'viewSuggest'])->name('driver-post.viewSuggest');
                Route::get('/accept-suggest-truck/{suggestTruckId}', [DriverPostController::class, 'acceptSuggestTruck'])->name("driver-post.acceptSuggestTruck");
                Route::get('/list-order/{orderType}', [DriverPostController::class, 'listOrder'])->name("driver-post.listOrder");
                Route::get('/list-suggest-truck', [DriverPostController::class, 'listSuggestTruck'])->name("driver-post.listSuggestTruck");
                Route::get('/completed-order/{orderInformationId}', [DriverPostController::class, 'completedOrder'])->name("driver-post.completedOrder");
            });

            Route::apiResource('driver', DriverController::class);
            Route::apiResource('driver-post', DriverPostController::class);

        //});
        //book truck
        //Route::middleware(['book_truck'])->group(function () {
            Route::prefix('customer-book-truck')->group(function () {
                Route::middleware(['check_balance'])->group(function () {
                    Route::post('/search-post', [CustomerBookTruckController::class, 'searchPost'])->name("customerBookTruck.search");
                    Route::get('/book-truck/{postId}', [CustomerBookTruckController::class, 'bookTruck'])->name("customerBookTruck.bookTruck");
                    Route::get('/accept-customer-book-order/{orderInformationId}', [CustomerBookTruckController::class, 'acceptDriver'])->name("customerBookTruck.acceptDriver");
                });
                Route::get('/view-post/{postId}', [CustomerBookTruckController::class, 'viewPost'])->name("customerBookTruck.view");
                Route::get('/cancel-order/{orderInformationId}', [CustomerBookTruckController::class, 'customerCancelOrder'])->name("customerBookTruck.customerCancelOrder");
                Route::get('/list-order/{orderType}', [CustomerBookTruckController::class, 'listOrder'])->name("customerBookTruck.listOrder");
                Route::get('/view-order/{orderInformationId}', [CustomerBookTruckController::class, 'viewOrder'])->name("customerBookTruck.viewOrder");
                Route::get('/completed-order/{orderInformationId}', [CustomerBookTruckController::class, 'completedOrder'])->name("customerBookTruck.completedOrder");
                Route::post('/review-driver/{orderInformationId}', [CustomerBookTruckController::class, 'reviewDriver'])->name("customerBookTruck.reviewDriver");
            });
            Route::apiResource('favorite-post', FavoritePostController::class);
        //});
        Route::apiResource('customer-notification', CustomerNotificationController::class);
        Route::prefix('customer-notification')->group(function () {
            Route::get('/read-customer-notification/{id}', [CustomerNotificationController::class, 'readCustomerNotification'])->name("customerNotification.readCustomerNotification");
        });
        Route::prefix('payment')->group(function () {
            Route::post('/add-money', [PaymentController::class, 'addMonney'])->name("payment.addMonney");
        });

    });
    Route::apiResource('report', ReportDriverController::class);
    Route::prefix('/report')->group(function () {
        Route::get('/read/{id}', [UserController::class, 'readReportDriver'])->name("User.readReportDriver");
    });
});




