<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ScaleController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\TimelineController;
use App\Http\Controllers\ModelKitController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//healthcheck
Route::get('/', function () {
    return response()->json(['message' => 'Hello this is gunpla API'], 200);
});

//scales
Route::apiResource('scales', ScaleController::class)->only(['index','show']);
Route::apiResource('scales', ScaleController::class)->except(['index', 'show'])->middleware(['auth:api', 'gateChecking:manage-content']);;

//grades
Route::apiResource('grades', GradeController::class)->only(['index','show']);
Route::apiResource('grades', GradeController::class)->except(['index', 'show'])->middleware(['auth:api', 'gateChecking:manage-content']);;

//timelines
Route::apiResource('timelines', TimelineController::class)->only(['index','show']);
Route::apiResource('timelines', TimelineController::class)->except(['index', 'show'])->middleware(['auth:api', 'gateChecking:manage-content']);;

//model kits
Route::apiResource('modelkits', ModelKitController::class)->only(['index','show']);
Route::apiResource('modelkits', ModelKitController::class)->except(['index', 'show'])->middleware(['auth:api', 'gateChecking:manage-content']);

//authentication
Route::get('/current_user', function (Request  $request) {
    return $request->user();
})->middleware('auth:api');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

//user management
Route::apiResource('users', UserController::class)->except(['store', 'update'])->middleware(['auth:api', 'gateChecking:manage-users']);