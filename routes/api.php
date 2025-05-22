<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\EmployerProfileController;


use App\Http\Controllers\PaymentController;

use App\Http\Controllers\CandidateProfileController;

use App\Http\Controllers\StripeController;


Route::middleware('auth:sanctum')->post('/stripe/checkout-session/{jobId}', [StripeController::class, 'createCheckoutSession']);
Route::post('/stripe/webhook', [StripeController::class, 'webhook']);

 Route::post('/jobs/filter', [JobController::class, 'filter']);
 Route::get('/jobs/locations', [JobController::class, 'getLocations']);


Route::get('/employers/{id}/jobs/analytics', [JobController::class, 'analytics']);

//Publish jobs
Route::get('/jobs/published', [JobController::class, 'GetPublishedJobs']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/payments/{job_id}', [PaymentController::class, 'store']);
    Route::put('/payments/{payment}', [PaymentController::class, 'update']);
    Route::delete('/payments/{payment}', [PaymentController::class, 'destroy']);
    
    Route::get('/candidates', [CandidateProfileController::class, 'index']);
    Route::post('/candidates', [CandidateProfileController::class, 'store']);
    Route::get('/candidates/{id}', [CandidateProfileController::class, 'show']);
    Route::put('/candidates/{id}', [CandidateProfileController::class, 'update']);
    Route::delete('/candidates/{id}', [CandidateProfileController::class, 'destroy']);
    // routes/api.php
    Route::get('/candidates/user/{userId}', [CandidateProfileController::class, 'getByUserId']);

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
    Route::get('/employers/jobs/{id}', [JobController::class, 'getEmployerJobs']);
    Route::get('/employer-profile', [EmployerProfileController::class, 'show']);
    Route::post('/employer-profile', [EmployerProfileController::class, 'store']);
    Route::get('/employer/applications/{id}', [ApplicationController::class, 'getApplicationsByEmployer'])->name('getApplicationsByEmployer', [EmployerProfileController::class, 'store']);
});





 Route::get('/applications/me', [ApplicationController::class, 'myApplications']);
Route::put('/applications/{id}/status', [ApplicationController::class, 'updateStatus']);
Route::delete('/applications/{id}/cancel', [ApplicationController::class, 'cancel']);
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
});
