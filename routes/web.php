<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PlantController;

// Halaman katalog (index)
Route::get('/', [PlantController::class, 'index'])->name('home');

// Halaman detail tanaman (show)
Route::get('/plant/{plant:slug}', [PlantController::class, 'show'])->name('plant.show');