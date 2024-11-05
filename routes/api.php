<?php

use App\Http\Controllers\API\AdvertisementController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ApplicationController;
use App\Http\Controllers\API\ContactController;
use App\Http\Controllers\API\JuriesController;
use App\Http\Controllers\API\SettingsController;
use App\Http\Controllers\API\SponsorsController;

Route::middleware('auth:sanctum')->group(function () {
    Route::post('complete-data', [ApplicationController::class, 'postApplication']);
    Route::post('/applications/post-videos', [ApplicationController::class, 'postApplicationVideos']);
    Route::post('rate-application/{application}', [ApplicationController::class, 'rateApplication']);
    Route::get('get-application/{application}', [ApplicationController::class, 'getApplication']);
    Route::get('get-user-application', [ApplicationController::class, 'getUserApplication']);
    Route::get('applications', [ApplicationController::class, 'getApplications']);
});

Route::get('juries', [JuriesController::class, 'getAllJuries']);
Route::get('advertisements', [AdvertisementController::class, 'getAll']);
Route::get('sponsors', [SponsorsController::class, 'getAll']);
Route::get('settings', [SettingsController::class, 'get']);
Route::get('samples', [ApplicationController::class, 'getSamples']);
Route::post('contact-us', [ContactController::class, 'postMessage']);
Route::post('days-left', [SettingsController::class, 'daysLeft']);

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('profile', [AuthController::class, 'profile']);
    Route::post('update-profile', [AuthController::class, 'updateProfile']);
});
