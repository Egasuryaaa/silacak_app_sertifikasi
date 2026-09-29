<?php

use App\Http\Controllers\Web\LabelPrinterController;
use App\Http\Controllers\Web\ShipmentWebController;
use Illuminate\Support\Facades\Route;

// 1. Halaman Lacak Resi Publik
Route::get('/', [ShipmentWebController::class, 'trackingPage'])->name('tracking.index');
Route::get('/track', [ShipmentWebController::class, 'trackingPage'])->name('tracking.search');

// 2. Halaman Loket Transaksi & Kalkulasi Ongkir
Route::get('/loket', [ShipmentWebController::class, 'shipmentDeskPage'])->name('desk.index');

// 3. Cetak Label PDF Termal
Route::get('/shipments/{trackingNumber}/print-label', [LabelPrinterController::class, 'printLabel'])
    ->name('shipments.print-label');