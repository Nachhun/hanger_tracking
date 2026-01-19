<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\HangerEntryController;
use App\Http\Controllers\BrandController;

Route::post('/auth/telegram', [AuthController::class, 'telegramAuth']);
Route::post('/auth/login-by-id', [AuthController::class, 'loginByTelegramId']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Salesman Routes
    // Salesman Routes
    Route::post('/entries', [HangerEntryController::class, 'store']);
    Route::get('/entries', [HangerEntryController::class, 'index']); // List with pagination
    Route::put('/entries/{id}', [HangerEntryController::class, 'update']);
    Route::delete('/entries/{id}', [HangerEntryController::class, 'destroy']);
    Route::get('/entries/export', [HangerEntryController::class, 'exportCSV']);
    Route::get('/entries/history', [HangerEntryController::class, 'history']); // Keep legacy for dashboard stats if needed
    Route::get('/entries/stats/today', [HangerEntryController::class, 'statsToday']);
    Route::get('/entries/dashboard', [HangerEntryController::class, 'dashboardStats']);
    Route::get('/provinces', [\App\Http\Controllers\ProvinceController::class, 'index']);
    Route::get('/brands/active', [BrandController::class, 'active']);

    // Admin Routes (role check done in controller)
    Route::prefix('admin')->group(function () {
        Route::get('/users', [AdminController::class, 'users']);
        Route::get('/users/{id}', [AdminController::class, 'show']);
        Route::patch('/users/{id}/toggle-status', [AdminController::class, 'toggleStatus']);
        Route::post('/users', [AdminController::class, 'createUser']);
        Route::get('/analytics', [AdminController::class, 'analytics']);
        Route::get('/entries', [AdminController::class, 'getAllEntries']);

        Route::get('/report', [AdminController::class, 'report']);
        Route::get('/report/export', [AdminController::class, 'exportReport']);

        // Province Management
        Route::post('/provinces', [\App\Http\Controllers\ProvinceController::class, 'store']);
        Route::put('/provinces/{id}', [\App\Http\Controllers\ProvinceController::class, 'update']);
        Route::delete('/provinces/{id}', [\App\Http\Controllers\ProvinceController::class, 'destroy']);

        // Brand Management
        Route::get('/brands', [BrandController::class, 'index']);
        Route::post('/brands', [BrandController::class, 'store']);
        Route::put('/brands/{id}', [BrandController::class, 'update']);
        Route::delete('/brands/{id}', [BrandController::class, 'destroy']);
    });
});

