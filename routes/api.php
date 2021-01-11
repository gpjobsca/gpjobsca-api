<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JobController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserJobsController;

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

Route::get('/', function () {
    return [
        'link' => env('APP_URL', null),
        'version' => 1.0
    ];
});

Route::apiResource('jobs', JobController::class)->only(['index', 'show']);

// This route needs to be protected
Route::middleware('auth:sanctum')->group(function () {
    Route::get('user', [UserController::class, 'getAuthenticatedUser']);
    Route::apiResource('jobs', JobController::class)->only(['store', 'update', 'destroy']);
    Route::apiResource('user/{id}/jobs', UserJobsController::class)->only(['index']);
});
