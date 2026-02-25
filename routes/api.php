<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CvController;
use App\Http\Controllers\CertificateController;

// --- PUBLIC ROUTES (tanpa perlu token) ---
Route::post('/login', [AuthController::class, 'login']);
Route::get('/cv', [CvController::class, 'index']); 
Route::get('/projects', [ProjectController::class, 'index']);
Route::get('/certificates', [CertificateController::class, 'index']);

// --- ROUTE RAHASIA ---
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    
    // Perbaikan: Gunakan ::class di sini juga
    Route::post('/cv', [CvController::class, 'store']);
    Route::delete('/cv/{id}', [CvController::class, 'destroy']);
    Route::post('/projects', [ProjectController::class, 'store']);
    Route::post('/projects/{id}', [ProjectController::class, 'update']);
    Route::delete('/projects/{id}', [ProjectController::class, 'destroy']);
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');