<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PlantController;
use App\Http\Controllers\AdminPlantController;

// Halaman katalog (index)
Route::get('/', [PlantController::class, 'index'])->name('home');

// Halaman detail tanaman (show)
Route::get('/plant/{plant:slug}', [PlantController::class, 'show'])->name('plant.show');

Route::get('/admin/login', [AdminPlantController::class, 'login'])->name('admin.login');
Route::post('/admin/login', [AdminPlantController::class, 'authenticate'])->name('admin.authenticate');

Route::prefix('admin')->name('admin.')->middleware('admin')->group(function () {
	Route::post('/logout', [AdminPlantController::class, 'logout'])->name('logout');
	Route::resource('plants', AdminPlantController::class)->except(['show']);
});