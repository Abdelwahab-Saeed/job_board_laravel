<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\ApplicationController;

use App\Http\Controllers\PaymentController;

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/payments/{job_id}', [PaymentController::class, 'store']);
    Route::put('/payments/{payment}', [PaymentController::class, 'update']);
    Route::delete('/payments/{payment}', [PaymentController::class, 'destroy']);
});




Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
});

Route::apiResource('categories', CategoryController::class);

Route::apiResource('jobs', JobController::class);

// Route::post('/jobs/{jobId}/apply-test', [ApplicationController::class, 'apply']);




Route::middleware('auth:sanctum')->group(function () {
   
    Route::post('/jobs/{jobId}/apply', [ApplicationController::class, 'apply']);
    
    Route::get('/applications/me', [ApplicationController::class, 'myApplications']);
    Route::put('/applications/{id}/status', [ApplicationController::class, 'updateStatus']);
    Route::delete('/applications/{id}/cancel', [ApplicationController::class, 'cancel']);
});
