<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TrainController;
use App\Http\Controllers\StationController;
use App\Http\Controllers\TrackCircuitController;

Route::get('/trains', [TrainController::class, 'index']);
Route::get('/stations', [StationController::class, 'index']);

// Return all track circuits from the database
Route::get('/track-circuits', [TrackCircuitController::class, 'index']);

// Return a single track circuit by circuit_id
Route::get('/track-circuits/{circuitId}', [TrackCircuitController::class, 'show']);

// Optional: endpoint to trigger fetch from WMATA API (for testing)
// You might want to protect this with auth in production
Route::get('/track-circuits/fetch', [TrackCircuitController::class, 'fetchFromApi']);
