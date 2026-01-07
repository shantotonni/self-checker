<?php

use App\Http\Controllers\Api\AdminDashboardController;

use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\DealerController;
use App\Http\Controllers\Api\MenuController;
use App\Http\Controllers\Api\MenuPermissionController;
use App\Http\Controllers\Api\Mobile\CommonController;
use App\Http\Controllers\Api\Mobile\ReportController;
use App\Http\Controllers\Api\OutletConfigurationController;
use App\Http\Controllers\Api\OutletConnectionController;
use App\Http\Controllers\Api\OutletController;
use App\Http\Controllers\Api\OutletSessionController;
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
        // Specific routes FIRST
        Route::get('/active', [OutletController::class, 'getActiveOutlets']);
        Route::get('/search/{query}', [OutletController::class, 'search']);
        Route::post('/test-connection', [OutletController::class, 'testConnection']);

        // Resource routes LAST
        Route::get('/', [OutletController::class, 'index']);
        Route::post('/', [OutletController::class, 'store']);
        Route::get('/{outlet}', [OutletController::class, 'show']);
        Route::put('/{outlet}', [OutletController::class, 'update']);
        Route::delete('/{outlet}', [OutletController::class, 'destroy']);
    });

    Route::prefix('outlet-configurations')->group(function () {
        Route::get('/', [OutletConfigurationController::class, 'index']);
        Route::post('/', [OutletConfigurationController::class, 'store']);
        Route::get('/{outletConfiguration}', [OutletConfigurationController::class, 'show']);
        Route::put('/{outletConfiguration}', [OutletConfigurationController::class, 'update']);
        Route::delete('/{outletConfiguration}', [OutletConfigurationController::class, 'destroy']);

        Route::get('/search/{query}', [OutletConfigurationController::class, 'search']);
        Route::post('/test-connection', [OutletConfigurationController::class, 'testConnection']);
        Route::post('/{outletConfiguration}/execute', [OutletConfigurationController::class, 'execute']);
        Route::post('/{outletConfiguration}/retry', [OutletConfigurationController::class, 'retry']);
        Route::post('/bulk-execute', [OutletConfigurationController::class, 'bulkExecute']);
        Route::get('/stats/pending-count', [OutletConfigurationController::class, 'getPendingCount']);
        Route::get('/status/{status}', [OutletConfigurationController::class, 'getByStatus']);
    });

    Route::prefix('outlet-connection')->group(function () {

        // Test database connection
        Route::post('/test', [OutletConnectionController::class, 'testConnection']);

        // Session management
        Route::post('/set-session', [OutletConnectionController::class, 'setSession']);
        Route::get('/get-session', [OutletConnectionController::class, 'getSession']);
        Route::post('/clear-session', [OutletConnectionController::class, 'clearSession']);

        // Execute on session outlet
        Route::post('/execute', [OutletConnectionController::class, 'executeOnSession']);
    });

    Route::prefix('outlet-session')->group(function () {
        Route::post('/switch', [OutletSessionController::class, 'switch']);
        Route::post('/test', [OutletSessionController::class, 'test']);
        Route::get('/current', [OutletSessionController::class, 'current']);
        Route::post('/clear', [OutletSessionController::class, 'clear']);
        Route::post('/execute', [OutletSessionController::class, 'execute']);
    });


    //change-password
    Route::post('change-password', [SettingController::class, 'changePassword']);

});


