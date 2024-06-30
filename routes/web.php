<?php

use App\Http\Controllers\HistoriesController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ScooterController;

Route::get('/', [HomeController::class, 'index'])->name('home');

// Scooter
Route::get('/scooter', [ScooterController::class, 'index'])->name('scooter');
Route::post('/scooter', [ScooterController::class, 'store'])->name('scooter-create');
Route::post('/scooter/{id}', [ScooterController::class, 'edit'])->name('scooter-edit');
Route::delete('/scooter/{id}', [ScooterController::class, 'destroy'])->name('scooter-delete');

// Passenger
Route::post('/passenger', [HomeController::class, 'store'])->name('passenger');
Route::get('/map/{passenger_id}', [HistoriesController::class, 'history']);

// History
Route::get('/history', [HistoriesController::class, 'index'])->name('history');;
