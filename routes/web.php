<?php

use App\Http\Controllers\HistoriesController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ScooterController;

Route::get('/', [HomeController::class, 'index'])->name('home');

// Scooter
Route::get('/scooter', [ScooterController::class, 'index'])->name('scooter');
Route::post('/scooter', [ScooterController::class, 'store'])->name('scooter-create');

// Passenger
Route::post('/passenger', [HomeController::class, 'store'])->name('passenger');
Route::get('/map/{passenger_id}', [HistoriesController::class, 'index']);
