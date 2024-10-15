<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ApplicationController;

Route::middleware('auth:sanctum')->group(function () {
    Route::post('post-application', [ApplicationController::class, 'postApplication']);
    Route::post('rate-application/{application}', [ApplicationController::class, 'rateApplication']);
    Route::get('applications', [ApplicationController::class, 'getApplications']);
    Route::get('get-application/{application}', [ApplicationController::class, 'getApplication']);
});

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('profile', [AuthController::class, 'profile']);
    Route::post('update-profile', [AuthController::class, 'updateProfile']);
});
