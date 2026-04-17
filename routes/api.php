<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Controllers
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\settings\SystemSettingsController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ReportDataController;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\API\LoginController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

// 1. THIS IS THE ACTUAL LOGIN ACTION (Must be POST)
Route::post('/login', [LoginController::class, 'login']);

// 2. This is the "Security Net" (If someone hits a protected route without a token)
Route::get('/login', function () {
    return response()->json(['message' => 'Unauthenticated.'], 401);
})->name('login');


/*
|--------------------------------------------------------------------------
| Protected Routes (Sanctum)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {

    // Logout
    Route::post('/logout', [LoginController::class, 'logout']);

    // Project Details (Inside protected group for security)
   Route::get('/{id}', [ProjectController::class, 'show']);

     // Ensure the URL matches exactly what the frontend is calling
    Route::post('/projects/{id}/upload', [ProjectController::class, 'uploadDocument']);
    
   
    
    // Dashboard
    Route::get('/dashboard', function() {
        return response()->json(['message' => 'Welcome to dashboard']);
    });

    /*
    |--------------------------------------------------------------------------
    | Project Routes
    |--------------------------------------------------------------------------
    */
    Route::prefix('projects')->group(function () {
        Route::post('/', [ProjectController::class, 'create']);
        Route::get('/', [ProjectController::class, 'index']);
        Route::delete('/{projectCode}', [ProjectController::class, 'delete']);
    });


   

    /*
    |--------------------------------------------------------------------------
    | Other Routes
    |--------------------------------------------------------------------------
    */
    Route::post('/system-settings', [SystemSettingsController::class, 'create']);
    Route::get('/send-welcome-email', [EmailController::class, 'index']);

    Route::prefix('report')->group(function () {
        Route::get('/data-report', [ReportDataController::class, 'show']);
        Route::get('/data-report2', [ReportDataController::class, 'show2']);
    });
    
});