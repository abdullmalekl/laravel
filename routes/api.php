<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BoardsController;
use App\Http\Controllers\CloudController;
use App\Http\Controllers\ConversationController;
use App\Http\Controllers\LessonsController;
use App\Http\Controllers\PlatformPrjectsController;
use App\Http\Controllers\ProjectsController;
use App\Http\Controllers\SensorsController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::post('register' , [AuthController::class ,'register']);
Route::post('login', [AuthController::class , 'login']);
Route::get('projects/parts/{id}' ,[PlatformPrjectsController::class , 'edit'] );
Route::get('lessons/parts/{id}' ,[LessonsController::class , 'edit'] );
Route::apiResource('PlatformProjects' ,PlatformPrjectsController::class );
Route::apiResource('lessons' ,LessonsController::class );
Route::put('cloud/v1', [CloudController::class , 'show']);

Route::post('froala/upload_image' , [LessonsController::class , 'upload_image']);
Route::post('lessons/parts' ,[LessonsController::class , 'parts'] );
Route::post('projects/parts' ,[PlatformPrjectsController::class , 'parts'] );
// protected with user token

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('userDetails', [AuthController::class , 'update']);
    Route::get('users', [AuthController::class , 'index']);
    Route::put('cloud/v1/Monitoring', [CloudController::class , 'control']);
    Route::delete('user/{id}', [AuthController::class , 'destroy']);
    Route::post('reset', [AuthController::class , 'reset']);
    Route::apiResource('projects' ,ProjectsController::class);
    Route::apiResource('messages' ,ConversationController::class);
    Route::apiResource('sensors' ,SensorsController::class);
    Route::post('sensors/delete/{id}' ,[SensorsController::class , 'destroy']);
    Route::get('dash/devices' ,[BoardsController::class , 'dashIndex']);
    Route::apiResource('devices' ,BoardsController::class);
    Route::post('logout', [AuthController::class , 'logout']);
    Route::get('refresh', [AuthController::class , 'refresh']);
    Route::get('me', [AuthController::class , 'me']);
    Route::get('menow', [AuthController::class , 'menow']);
});