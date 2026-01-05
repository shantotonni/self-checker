<?php

use App\Http\Controllers\Api\AdminDashboardController;

use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\DealerController;
use App\Http\Controllers\Api\MenuController;
use App\Http\Controllers\Api\MenuPermissionController;
use App\Http\Controllers\Api\Mobile\CommonController;
use App\Http\Controllers\Api\Mobile\ReportController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\SettingController;
use App\Http\Controllers\Api\ShowroomController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BusinessProductController;
use App\Http\Controllers\CustomerAuthController;
use App\Http\Controllers\MokamController;
use App\Http\Controllers\ProductPriceController;
use Illuminate\Support\Facades\Route;

Route::post('login', [AuthController::class, 'login']);
Route::post('mobile-sign-up', [AuthController::class, 'mobileSignUp']);
Route::post('customer-send-otp', [AuthController::class, 'sendOtp']);
Route::post('customer-reset-password', [AuthController::class, 'resetPasswordWithOtp']);


Route::post('dashboard-login', [CustomerAuthController::class, 'dashboardLogin']);

Route::group(['middleware' => 'jwtauth:api'], function () {

    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('me', [AuthController::class, 'me']);

    Route::post('get-all-districts', [CommonController::class, 'getAllDistricts']);
    Route::post('get-all-upazilla-mokam-by-district', [CommonController::class, 'getAllUpazillaMokamByDistricts']);

    Route::post('get-district-wise-daily-food-price-report', [ReportController::class, 'getDistrictWiseDailyFoodPriceReport']);

    //user api route
    Route::apiResource('user', UserController::class);
    Route::get('search/user/{query}', [UserController::class, 'search']);
    Route::get('get-all-users/', [UserController::class, 'getAllUser']);
    Route::get('user-by-user-id', [UserController::class, 'getUserByUserId']);
    Route::post('user-profile-update', [UserController::class, 'updateProfile']);

    //menu resource route
    Route::apiResource('menu', MenuController::class);
    Route::get('search/menu/{query}', [MenuController::class, 'search']);
    Route::get('get-all-menu', [MenuController::class, 'getAllMenu']);

    //menu permission route
    Route::get('get-user-menu-details/{UserID}', [MenuPermissionController::class, 'getUserMenuPermission']);
    Route::get('sidebar-get-all-user-menu', [MenuPermissionController::class, 'getSidebarAllUserMenu']);
    Route::post('save-user-menu-permission', [MenuPermissionController::class, 'saveUserMenuPermission']);

    //customer resource route
    Route::apiResource('customer', CustomerController::class);
    Route::get('search/customer/{query}', [CustomerController::class, 'search']);

    //Role resource route
    Route::apiResource('role', RoleController::class);
    Route::get('search/role/{query}', [RoleController::class, 'search']);


    //change-password
    Route::post('change-password', [SettingController::class, 'changePassword']);

    //common route
    Route::get('get-all-brand', [CommonController::class, 'getAllBrand']);

    Route::get('get-all-district-by-area', [CommonController::class, 'getAllDistrictByArea']);

    //admin dashboard
    Route::get('get-all-admin-dashboard-data', [AdminDashboardController::class, 'getAdminDashboardAllData']);


    //Showroom
    Route::apiResource('showroom-list', ShowroomController::class);
    Route::get('search/showroom-list/{query}', [ShowroomController::class, 'search']);


    Route::apiResource('dealer-list', DealerController::class);
    Route::get('search/dealer-list/{query}', [DealerController::class, 'search']);

    Route::prefix('business-products')->group(function () {
        Route::get('/', [BusinessProductController::class, 'index']);
        Route::get('/search/{query}', [BusinessProductController::class, 'search']);
        Route::get('/businesses', [BusinessProductController::class, 'getBusinesses']);
        Route::get('/{id}', [BusinessProductController::class, 'show']);
        Route::post('/', [BusinessProductController::class, 'store']);
        Route::put('/{id}', [BusinessProductController::class, 'update']);
        Route::patch('/{id}/toggle-status', [BusinessProductController::class, 'toggleStatus']);
        Route::delete('/{id}', [BusinessProductController::class, 'destroy']);
    });

    // Mokam Routes
    Route::prefix('mokams')->group(function () {
        Route::get('/', [MokamController::class, 'index']);
        Route::get('/search/{query}', [MokamController::class, 'search']);
        Route::get('/districts', [MokamController::class, 'getDistricts']);
        Route::get('/{id}', [MokamController::class, 'show']);
        Route::post('/', [MokamController::class, 'store']);
        Route::put('/{id}', [MokamController::class, 'update']);
        Route::patch('/{id}/toggle-status', [MokamController::class, 'toggleStatus']);
        Route::delete('/{id}', [MokamController::class, 'destroy']);
    });

    // Product Price Routes
    Route::prefix('product-prices')->group(function () {
        Route::get('/', [ProductPriceController::class, 'index']);
        Route::get('/search/{query}', [ProductPriceController::class, 'search']);
        Route::get('/products', [ProductPriceController::class, 'getProducts']);
        Route::get('/locations', [ProductPriceController::class, 'getLocations']);
        Route::get('/sample-excel', [ProductPriceController::class, 'sampleExcel']);
        Route::get('/export', [ProductPriceController::class, 'export']);
        Route::get('/{id}/history', [ProductPriceController::class, 'history']);
        Route::get('/{id}', [ProductPriceController::class, 'show']);
        Route::post('/', [ProductPriceController::class, 'store']);
        Route::post('/import', [ProductPriceController::class, 'import']);
        Route::put('/{id}', [ProductPriceController::class, 'update']);
        Route::delete('/{id}', [ProductPriceController::class, 'destroy']);
    });


});


Route::group(['middleware' => 'CustomerAuth'], function () {
    //get data
    Route::get('get-all-problem-section', [CommonController::class, 'getAllProblemSection']);
    Route::get('get-all-harvester-cost', [CommonController::class, 'getAllHarvesterCost']);

    //service request
    Route::post('customer-service-request', [App\Http\Controllers\Api\Mobile\ServiceRequestController::class,'customerServiceRequest']);
    Route::get('get-all-customer-service-request', [App\Http\Controllers\Api\Mobile\ServiceRequestController::class,'getAllCustomerServiceRequest']);

    Route::post('auth/profile-update', [CustomerAuthController::class, 'updateProfileMobile']);
    Route::post('customer-password-change', [CustomerAuthController::class, 'changePassword']);

    Route::post('customer-service-request', [App\Http\Controllers\Api\Mobile\ServiceRequestController::class, 'customerServiceRequest']);
    //District wise seasonal crops

    Route::post('/harvester-warranty-parts', [App\Http\Controllers\Api\Mobile\CustomerController::class,'warrantyParts']);

    Route::post('/harvester-smart-assist', [App\Http\Controllers\Api\Mobile\CustomerController::class,'harvesterSmartAssist']);
    Route::get('/get-customer-profile', [App\Http\Controllers\Api\Mobile\CustomerController::class,'getCustomerProfile']);
    Route::post('/customer-feedback', [App\Http\Controllers\Api\Mobile\CustomerController::class,'customerFeedback']);
    Route::get('/get-harvester-warranty', [App\Http\Controllers\Api\Mobile\ServiceRequestController::class,'getHarvesterWarranty']);

});


