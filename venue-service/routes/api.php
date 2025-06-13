<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VenueController;


Route::get('/venues', [VenueController::class, 'index']);
Route::get('/venues/{id}', [VenueController::class, 'show']);
Route::post('/venues', [VenueController::class, 'store']);
Route::patch('/venues/{id}/decrement-slot', [VenueController::class, 'decrementSlot']);
Route::delete('/venues/{id}', [VenueController::class, 'destroy']);
Route::put('/venues/{id}', [VenueController::class, 'update']);
Route::patch('/venues/{id}/increment-slot', [VenueController::class, 'incrementSlot']);
