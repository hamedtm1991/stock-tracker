<?php

use App\Http\Controllers\Api\StockController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\StockUploadController;

Route::get('stocks/upload', [StockUploadController::class, 'showForm']);
Route::post('stocks/upload', [StockUploadController::class, 'upload']);


Route::prefix('api')->middleware('api')->group(function () {
    Route::get('stocks/period/{period}', [StockController::class, 'byPeriod']);
    Route::get('stocks/custom', [StockController::class, 'byCustomDates']);
});
