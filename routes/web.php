<?php

use App\Http\Controllers\BoardGameController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\MenuController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'home')->name('home');

Route::get('/booking', [BookingController::class, 'create'])->name('bookings.create');
Route::post('/booking', [BookingController::class, 'store'])->name('bookings.store');

Route::get('/games', [BoardGameController::class, 'index'])->name('games.index');

Route::get('/menu', [MenuController::class, 'index'])->name('menu.index');
