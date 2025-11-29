<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\UserController;
use App\Http\Controllers\Api\V1\ContactController;
use App\Http\Controllers\Api\V1\BuildingController;
use App\Http\Controllers\Api\V1\AssetController;
use App\Http\Controllers\Api\V1\PartController;
use App\Http\Controllers\Api\V1\InventoryLocationController;
use App\Http\Controllers\Api\V1\PartInventoryController;
use App\Http\Controllers\Api\V1\TaskController;
use App\Http\Controllers\Api\V1\TaskPartController;
use App\Http\Controllers\Api\V1\ServiceReportController;
use App\Http\Controllers\Api\V1\InvoiceController;
use App\Http\Controllers\Api\V1\InvoiceLineItemController;
use App\Http\Controllers\Api\V1\AuthController;

Route::prefix('v1')->group(function () {
    // Simple Auth (no tokens) - returns user data on login
    Route::post('auth/login', [AuthController::class, 'login']);

    Route::apiResource('users', UserController::class);
    Route::apiResource('contacts', ContactController::class);
    Route::apiResource('buildings', BuildingController::class);
    Route::apiResource('assets', AssetController::class);
    Route::apiResource('parts', PartController::class);
    Route::apiResource('inventory-locations', InventoryLocationController::class);

    Route::get('part-inventory', [PartInventoryController::class, 'index']);
    Route::post('part-inventory', [PartInventoryController::class, 'store']);
    Route::patch('part-inventory', [PartInventoryController::class, 'update']);
    Route::delete('part-inventory', [PartInventoryController::class, 'destroy']);

    Route::get('tasks/{task}/parts', [TaskPartController::class, 'index']);
    Route::post('tasks/{task}/parts', [TaskPartController::class, 'store']);
    Route::patch('tasks/{task}/parts/{part}', [TaskPartController::class, 'update']);
    Route::delete('tasks/{task}/parts/{part}', [TaskPartController::class, 'destroy']);

    Route::apiResource('tasks', TaskController::class);
    Route::apiResource('service-reports', ServiceReportController::class);
    Route::post('service-reports/{service_report}/attachment', [ServiceReportController::class, 'uploadAttachment']);
    Route::apiResource('invoices', InvoiceController::class);
    Route::apiResource('invoice-line-items', InvoiceLineItemController::class);
});


