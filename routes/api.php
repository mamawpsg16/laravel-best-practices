<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\Task\TaskController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Report\ReportController;
use App\Http\Controllers\Auth\AuthenticationController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/



Route::middleware(['auth:sanctum', 'banned'])->group(function () {

  
    Route::get('/user',[AuthenticationController::class, 'user']);
    Route::post('/user/change-password',[AuthenticationController::class, 'changePassword']);
    Route::get('/posts',[PostController::class, 'index']);
    Route::post('/logout', [AuthenticationController::class,'logout'])->name('logout');
    
    Route::apiResource('users', UserController::class);
    Route::put('/users/{user}/ban', [UserController::class, 'banUser']);

    /** REPORT */
    Route::prefix('reports')->group(function () {
        Route::get('/replies', [ReportController::class, 'getReplies']);
        Route::get('/types', [ReportController::class, 'getReportTypes']);
        Route::put('mark-as-read/{report}', [ReportController::class, 'markAsRead']);
        Route::post('send-message', [ReportController::class, 'reply']);
    });

    Route::apiResource('/reports', ReportController::class);

    Route::prefix('admin')->group(function () {
    });
    
    Route::get('/tasks-status',[TaskController::class, 'getTaskStatusDetails']);
    Route::apiResource('/tasks',TaskController::class);
    Route::prefix('tasks')->group(function () {
        Route::post('/update-status',[TaskController::class, 'updateStatus']);
        Route::post('/update-order',[TaskController::class, 'updateOrder']);
        Route::put('/restore/{task}',[TaskController::class, 'restoreTask']);
        Route::post('/update-tasks',[TaskController::class, 'updateTasks']);
    });
    
    Route::prefix('email')->group(function () {
        Route::post('/resend-verification', [AuthenticationController::class,'resendVerification'])->middleware(['throttle:6,1'])->name('verification.send');
    });
}); 

 
