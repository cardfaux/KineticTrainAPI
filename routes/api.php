<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TrainController;
use App\Http\Controllers\StationController;

Route::get('/trains', [TrainController::class, 'index']);
Route::get('/stations', [StationController::class, 'index']);
