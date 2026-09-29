<?php

use App\Http\Controllers\Api\ShippingApiController;
use App\Http\Controllers\Api\ShipmentApiController;
use App\Http\Controllers\Api\TrackingApiController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\CustomerController;
use Illuminate\Support\Facades\Route;

// Lacak resi & hitung tarif publik (dilindungi throttling untuk mitigasi beban)
Route::middleware('throttle:300,1')->group(function () {
    Route::get('/track/{trackingNumber}', [TrackingApiController::class, 'track']);
    Route::post('/shipping/calculate', [ShippingApiController::class, 'calculate']);
});

// Endpoint Transaksi & Operasional Loket Cabang
Route::prefix('v1')->group(function () {
    Route::post('/shipments', [ShipmentApiController::class, 'store']);
    Route::post('/shipments/update-status', [TrackingApiController::class, 'updateStatus']);

    // Master Data
    Route::apiResource('branches', BranchController::class)->only(['index', 'store', 'show']);
    Route::apiResource('customers', CustomerController::class)->only(['index', 'store', 'show']);
});