<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Backend\Api\MarketController;
use App\Http\Controllers\Api\QuestController;
use App\Http\Controllers\Api\EquipmentController;

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
Route::post('user/login', [AuthController::class, 'login']);
Route::get('list-employee', [AuthController::class, 'employee']);
Route::middleware('auth:api')->group(function () {
    Route::get('/check-login', [AuthController::class, 'checkLogin']);
});

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
// private routes
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::group(['prefix' => 'user'], function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::post('create', [AuthController::class, 'create']);
        Route::post('update/{id}', [AuthController::class, 'update']);
        Route::post('export', [AuthController::class, 'export']);
        Route::get('info', [AuthController::class, 'info']);
        Route::get('roles', [AuthController::class, 'roles']);
        Route::get('list', [AuthController::class, 'list']);
        Route::get('list-employee', [AuthController::class, 'employee']);
        Route::get('store', [AuthController::class, 'store']);
        Route::post('add', [AuthController::class, 'add']);
        Route::get('get', [AuthController::class, 'getCourseEquipment']);

        Route::get('/', [AuthController::class, 'user']);
        Route::group(['prefix' => 'equipment'], function () {
            Route::get('list-inventory-items', [EquipmentController::class, 'getInventoryItems']);
            Route::get('list-equipped-items', [EquipmentController::class, 'getEquippedItems']);
            Route::post('remove-equipped-item', [EquipmentController::class, 'removeEquippedItem']);
            Route::post('use-equipment', [EquipmentController::class, 'useEquipment']);
        });
    });
    Route::group(['prefix' => 'role'], function () {
        Route::get('list', [RoleController::class, 'list']);
    });
    ## Route Market ##
    Route::group(['prefix' => 'market'], function () {
        Route::get('list', [MarketController::class, 'index']);
    });
    ## Route Quest ##
    Route::group(['prefix' => 'quest'], function () {
        Route::get('list', [QuestController::class, 'index']);
        Route::post('create', [QuestController::class, 'create']);
        Route::post('update', [QuestController::class, 'update']);
    });
});