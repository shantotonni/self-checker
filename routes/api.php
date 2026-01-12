<?php

use App\Http\Controllers\Api\AppVersionInfoController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\MenuController;
use App\Http\Controllers\Api\MenuPermissionController;
use App\Http\Controllers\Api\MobileTerminalController;

use App\Http\Controllers\Api\OutletConnectionController;
use App\Http\Controllers\Api\OutletController;
use App\Http\Controllers\Api\OutletSessionController;
use App\Http\Controllers\Api\PaymentREQInfoLogController;
use App\Http\Controllers\Api\PaymentSuccessInfoController;
use App\Http\Controllers\Api\PaymentTypeController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\SettingController;

use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::post('login', [AuthController::class, 'login']);

Route::group(['middleware' => 'jwtauth:api'], function () {

    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('me', [AuthController::class, 'me']);

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

    //Role resource route
    Route::apiResource('role', RoleController::class);
    Route::get('search/role/{query}', [RoleController::class, 'search']);

    Route::prefix('outlets')->group(function () {
        Route::get('/active', [OutletController::class, 'getActiveOutlets']);
        // Resource routes LAST
        Route::get('/', [OutletController::class, 'index']);
        Route::post('/', [OutletController::class, 'store']);
        Route::get('/{outlet}', [OutletController::class, 'show']);
        Route::put('/{outlet}', [OutletController::class, 'update']);
        Route::delete('/{outlet}', [OutletController::class, 'destroy']);

        Route::get('/search/{query}', [OutletController::class, 'search']);
        Route::post('/test-connection', [OutletController::class, 'testConnection']);
    });


    Route::prefix('outlet-session')->group(function () {
        Route::post('/switch', [OutletSessionController::class, 'switch']);
        Route::post('/test', [OutletSessionController::class, 'test']);
        Route::get('/current', [OutletSessionController::class, 'current']);
        Route::post('/clear', [OutletSessionController::class, 'clear']);
        Route::post('/execute', [OutletSessionController::class, 'execute']);
    });

    // Outlet Connection Routes
    Route::prefix('outlet-connection')->group(function () {
        Route::get('/outlets', [OutletConnectionController::class, 'getActiveOutlets']);
        Route::post('/test', [OutletConnectionController::class, 'testConnection']);
        Route::post('/set-active', [OutletConnectionController::class, 'setActiveOutlet']);
    });

    // Mobile Terminal Routes
    Route::prefix('mobile-terminals')->group(function () {
        Route::get('/', [MobileTerminalController::class, 'index']);
        Route::post('/', [MobileTerminalController::class, 'store']);
        Route::get('/device-types', [MobileTerminalController::class, 'getDeviceTypes']);
        Route::get('/printer-types', [MobileTerminalController::class, 'getPrinterTypes']);
        Route::get('/{id}', [MobileTerminalController::class, 'show']);
        Route::put('/{id}', [MobileTerminalController::class, 'update']);
        Route::delete('/{id}', [MobileTerminalController::class, 'destroy']);
        Route::post('/{id}/toggle-sync', [MobileTerminalController::class, 'toggleSync']);
    });

    // Payment Request Info Log Routes
    Route::prefix('payment-logs')->group(function () {
        Route::get('/', [PaymentREQInfoLogController::class, 'index']);
        Route::post('/', [PaymentREQInfoLogController::class, 'store']);
        Route::get('/payment-types', [PaymentREQInfoLogController::class, 'getPaymentTypes']);
        Route::get('/terminals', [PaymentREQInfoLogController::class, 'getTerminals']);
        Route::get('/stats', [PaymentREQInfoLogController::class, 'getStats']);
        Route::get('/{id}', [PaymentREQInfoLogController::class, 'show']);
        Route::put('/{id}', [PaymentREQInfoLogController::class, 'update']);
        Route::delete('/{id}', [PaymentREQInfoLogController::class, 'destroy']);
    });

    // Payment Success Info Routes
    Route::prefix('payment-success')->group(function () {
        Route::get('/', [PaymentSuccessInfoController::class, 'index']);
        Route::post('/', [PaymentSuccessInfoController::class, 'store']);
        Route::get('/card-types', [PaymentSuccessInfoController::class, 'getCardTypes']);
        Route::get('/issuers', [PaymentSuccessInfoController::class, 'getIssuers']);
        Route::get('/terminals', [PaymentSuccessInfoController::class, 'getTerminals']);
        Route::get('/stats', [PaymentSuccessInfoController::class, 'getStats']);
        Route::get('/{id}', [PaymentSuccessInfoController::class, 'show']);
        Route::put('/{id}', [PaymentSuccessInfoController::class, 'update']);
        Route::delete('/{id}', [PaymentSuccessInfoController::class, 'destroy']);
    });

    // Payment Type Routes
    Route::prefix('payment-types')->group(function () {
        Route::get('/', [PaymentTypeController::class, 'index']);
        Route::post('/', [PaymentTypeController::class, 'store']);
        Route::get('/terminals', [PaymentTypeController::class, 'getTerminals']);
        Route::get('/stats', [PaymentTypeController::class, 'getStats']);
        Route::get('/{id}', [PaymentTypeController::class, 'show']);
        Route::put('/{id}', [PaymentTypeController::class, 'update']);
        Route::delete('/{id}', [PaymentTypeController::class, 'destroy']);
        Route::post('/{id}/toggle', [PaymentTypeController::class, 'togglePaymentMethod']);
    });

    // App Version Info Routes
    Route::prefix('app-versions')->group(function () {
        Route::get('/', [AppVersionInfoController::class, 'index']);
        Route::post('/', [AppVersionInfoController::class, 'store']);
        Route::get('/packages', [AppVersionInfoController::class, 'getPackageNames']);
        Route::get('/stats', [AppVersionInfoController::class, 'getStats']);
        Route::get('/{id}', [AppVersionInfoController::class, 'show']);
        Route::put('/{id}', [AppVersionInfoController::class, 'update']);
        Route::delete('/{id}', [AppVersionInfoController::class, 'destroy']);
        Route::post('/{id}/toggle-status', [AppVersionInfoController::class, 'toggleStatus']);
    });

    //change-password
    Route::post('change-password', [SettingController::class, 'changePassword']);

});


