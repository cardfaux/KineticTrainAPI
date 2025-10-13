<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TrainController;

Route::get('/trains', [TrainController::class, 'index']);
