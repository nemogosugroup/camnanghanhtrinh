<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\Api\MedalController;
use App\Http\Controllers\Backend\Api\CategoryMedalController;
use App\Http\Controllers\Backend\Api\CategoryMissionController;
use App\Http\Controllers\Backend\Api\SubCategoryMissionController;
use App\Http\Controllers\Backend\Api\MissionController;
use App\Http\Controllers\Backend\Api\LevelController;
use App\Http\Controllers\Backend\Api\EquipmentController;
use App\Http\Controllers\Backend\Api\CategoryEquipmentController;
use App\Http\Controllers\Backend\Api\TypeEquipmentController;
use App\Http\Controllers\Backend\Api\CategoryCourseController;
use App\Http\Controllers\Backend\Api\CourseController;
use App\Http\Controllers\Backend\Api\UserEquipmentController;
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
Route::group(['middleware' => ['auth:sanctum']], function () {    
    Route::group(['prefix' => 'medal'], function () {
        Route::get('list', [MedalController::class, 'index']);
        Route::post('create', [MedalController::class, 'create']);
        Route::post('update/{id}', [MedalController::class, 'update']);
        Route::post('delete/{id}', [MedalController::class, 'destroy']);
    });
    Route::group(['prefix' => 'category-medal'], function () {
        Route::get('list', [CategoryMedalController::class, 'index']);
        Route::post('create', [CategoryMedalController::class, 'create']);
        Route::post('update/{id}', [CategoryMedalController::class, 'update']);
        Route::post('delete/{id}', [CategoryMedalController::class, 'destroy']);
    });
    Route::group(['prefix' => 'category-mission'], function () {      
        Route::get('list', [CategoryMissionController::class, 'index']);
        Route::post('create', [CategoryMissionController::class, 'create']);
        Route::post('update/{id}', [CategoryMissionController::class, 'update']);
        Route::post('delete/{id}', [CategoryMissionController::class, 'destroy']);
    });
    Route::group(['prefix' => 'sub-category-mission'], function () {
        Route::get('list', [SubCategoryMissionController::class, 'index']);
        Route::post('create', [SubCategoryMissionController::class, 'create']);
        Route::post('update/{id}', [SubCategoryMissionController::class, 'update']);
        Route::post('delete/{id}', [SubCategoryMissionController::class, 'destroy']);
    });
    Route::group(['prefix' => 'mission'], function () {
        Route::get('list', [MissionController::class, 'index']);
        Route::post('create', [MissionController::class, 'create']);
        Route::post('update/{id}', [MissionController::class, 'update']);
        Route::post('delete/{id}', [MissionController::class, 'destroy']);
    });
    Route::group(['prefix' => 'level'], function () {
        Route::get('list', [LevelController::class, 'index']);
        Route::post('create', [LevelController::class, 'create']);
        Route::post('update/{id}', [LevelController::class, 'update']);
        Route::post('delete/{id}', [LevelController::class, 'destroy']);
    });
    Route::group(['prefix' => 'category-equipment'], function () {
        Route::get('list', [CategoryEquipmentController::class, 'index']);
        Route::post('create', [CategoryEquipmentController::class, 'create']);
        Route::post('update/{id}', [CategoryEquipmentController::class, 'update']);
        Route::post('delete/{id}', [CategoryEquipmentController::class, 'destroy']);
    });
    Route::group(['prefix' => 'type-equipment'], function () {
        Route::get('list', [TypeEquipmentController::class, 'index']);
        Route::post('create', [TypeEquipmentController::class, 'create']);
        Route::post('update/{id}', [TypeEquipmentController::class, 'update']);
        Route::post('delete/{id}', [TypeEquipmentController::class, 'destroy']);
    });
    Route::group(['prefix' => 'equipment'], function () {
        Route::get('list', [EquipmentController::class, 'index']);
        Route::post('create', [EquipmentController::class, 'create']);
        Route::post('update/{id}', [EquipmentController::class, 'update']);
        Route::post('delete/{id}', [EquipmentController::class, 'destroy']);
    });
    ## Route khoá học ##
    Route::group(['prefix' => 'course'], function () {
        Route::get('list', [CourseController::class, 'index']);
        Route::post('create', [CourseController::class, 'create']);
        Route::post('update/{id}', [CourseController::class, 'update']);
        Route::post('delete/{id}', [CourseController::class, 'destroy']);
    });
    Route::group(['prefix' => 'category-course'], function () {
        Route::get('list', [CategoryCourseController::class, 'index']);
        Route::post('create', [CategoryCourseController::class, 'create']);
        Route::post('update/{id}', [CategoryCourseController::class, 'update']);
        Route::post('delete/{id}', [CategoryCourseController::class, 'destroy']);
    });
});

// API Test : add equipment for user
Route::group(['prefix' => 'user-equipment'], function () {
    Route::post('add', [UserEquipmentController::class, 'addEquipment']);
});
// API Test : add equipment for user