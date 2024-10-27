<?php

use App\Http\Controllers\DownloadController;
use App\Http\Controllers\SampleController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/unauthorized', function () {
    return response()->json(
    [
        "status" => false,
        "message" => "unauthenticated",
        "errors" => ["Your are not authenticated"],
        "data" => [],
        "notes" => []
    ]
    , 401);
});

Route::get('applications/{application}/video/download', [DownloadController::class, 'downloadVideo'])->name('application.video.download');

Route::post('/sample', [SampleController::class, 'updateOrCreate'])->name('sample.updateOrCreate');
