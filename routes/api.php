<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\AdminController;

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
 

Route::middleware('auth:sanctum')->group(function () {
    Route::put('/admin/jobs/{id}/approve', [JobController::class, 'approveJob']);
    Route::put('/admin/jobs/{id}/reject', [JobController::class, 'rejectJob']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/admin/jobs', [AdminController::class, 'getAllJobs']);
    Route::get('/admin/users', [AdminController::class, 'getAllUsers']);
    Route::get('/admin/stats', [AdminController::class, 'getPlatformStats']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::delete('/admin/comments/{id}', [CommentController::class, 'adminDestroy']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/admin/payments', [PaymentController::class, 'getAllPayments']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('categories', CategoryController::class)->except('index');
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/admin/search', [AdminController::class, 'search']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::put('/admin/users/{id}/disable', [AdminController::class, 'disableUser']);
    Route::delete('/admin/users/{id}', [AdminController::class, 'deleteUser']);
});
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/jobs/{job}/apply', [ApplicationController::class, 'apply']);
    Route::get('/my-applications', [ApplicationController::class, 'myApplications']);
    Route::delete('/applications/{id}/cancel', [ApplicationController::class, 'cancel']);
    Route::patch('/admin/applications/{id}/status', [ApplicationController::class, 'updateStatus']);
    Route::get('/admin/applications', [ApplicationController::class, 'index']);

});