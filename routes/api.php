<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\candidate_profiles;



use App\Http\Controllers\PaymentController;


Route::post('/jobs/filter', [JobController::class, 'filter']);


Route::get('/employers/{id}/jobs/analytics', [JobController::class, 'analytics']);


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

Route::get('/jobs', [JobController::class, 'index']);
Route::get('/jobs/{id}', [JobController::class, 'show']);

Route::get('/categories', [CategoryController::class, 'index']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::apiResource('jobs', JobController::class)->except('index', 'show');
    Route::apiResource('categories', CategoryController::class)->except('index');
});





 Route::get('/applications/me', [ApplicationController::class, 'myApplications']);
Route::put('/applications/{id}/status', [ApplicationController::class, 'updateStatus']);
Route::delete('/applications/{id}/cancel', [ApplicationController::class, 'cancel']);
Route::post('/jobs/{jobId}/apply', [ApplicationController::class, 'apply']);
Route::get('/jobs/{id}/comments', [CommentController::class, 'index']);
Route::get('/jobs/{jobId}/comments/user/{userId}', [CommentController::class, 'indexForUser']);
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/jobs/{jobId}/comments', [CommentController::class, 'store']);
    Route::delete('/comments/{id}', [CommentController::class, 'destroy']);
});




Route::middleware('auth:sanctum')->group(function () {
   
    Route::post('/jobs/{jobId}/apply', [ApplicationController::class, 'apply']);

    Route::get('/applications/me', [ApplicationController::class, 'myApplications']);
    Route::put('/applications/{id}/status', [ApplicationController::class, 'updateStatus']);
    Route::delete('/applications/{id}/cancel', [ApplicationController::class, 'cancel']);
    Route::apiResource('candidates', CandidateProfileController::class);

});
