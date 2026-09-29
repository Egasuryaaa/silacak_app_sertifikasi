<?php

use App\Http\Controllers\Web\LabelPrinterController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Cetak label PDF termal
Route::get('/shipments/{trackingNumber}/print-label', [LabelPrinterController::class, 'printLabel'])
    ->name('shipments.print-label');