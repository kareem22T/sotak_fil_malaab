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
use App\Http\Middleware\EnsureEmailIsVerified;

Route::middleware('auth:sanctum')->group(function () {
    Route::post('complete-data', [ApplicationController::class, 'postApplication'])->middleware(EnsureEmailIsVerified::class);
    Route::post('/applications/post-videos', [ApplicationController::class, 'postApplicationVideos'])->middleware(EnsureEmailIsVerified::class);
    Route::get('/reels/{reel_id}', [ApplicationController::class, 'getVideoById'])->middleware(EnsureEmailIsVerified::class);
    Route::get('/sorted-applications', [ApplicationController::class, 'getApplicationsFUllNotAsReels'])->middleware(EnsureEmailIsVerified::class);
    Route::post('rate-application/{application}', [ApplicationController::class, 'rateApplication'])->middleware(EnsureEmailIsVerified::class);
    Route::get('get-application/{application}', [ApplicationController::class, 'getApplication']);
    Route::get('get-user-application', [ApplicationController::class, 'getUserApplication'])->middleware(EnsureEmailIsVerified::class);
    Route::get('applications', [ApplicationController::class, 'getApplications']);
    Route::get('check-if-application-exists', [ApplicationController::class, 'checkIsApplicationExists']);
});

Route::get('juries', [JuriesController::class, 'getAllJuries']);
Route::get('advertisements', [AdvertisementController::class, 'getAll']);
Route::get('sponsors', [SponsorsController::class, 'getAll']);
Route::get('settings', [SettingsController::class, 'get']);
Route::get('samples', [ApplicationController::class, 'getSamples'])->middleware('auth:sanctum');
Route::post('contact-us', [ContactController::class, 'postMessage']);
Route::get('days-left', [SettingsController::class, 'daysLeft']);

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('profile', [AuthController::class, 'profile']);
    Route::post('update-profile', [AuthController::class, 'updateProfile']);
});

Route::get('/user/ask-email-verfication-code', [AuthController::class, "askEmailCode"]);
Route::post('/user/verify-email', [AuthController::class, "verifyEmail"])->middleware('auth:sanctum');
Route::post('/user/change-password', [AuthController::class, "changePassword"])->middleware('auth:sanctum');
Route::post('/user/ask-for-forgot-password-email-code', [AuthController::class, "askEmailCodeForgot"]);
Route::post('/user/forgot-password', [AuthController::class, "forgetPassword"]);
Route::post('/user/forgot-password-check-code', [AuthController::class, "checkCodeForgot"]);
