<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookingController;



Route::apiResource('bookings', BookingController::class)->only([
    'index', 'store', 'show', 'destroy'
]);
Route::post('/bookings/no-event', [BookingController::class, 'storeWithoutRabbit']); 
Route::put('/bookings/{id}', [BookingController::class, 'update']);
