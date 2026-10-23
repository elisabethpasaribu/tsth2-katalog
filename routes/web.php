<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PlantController;
use App\Http\Controllers\AdminPlantController;
use App\Http\Controllers\AdminCategoryController; // <-- Tambahkan import ini

// Halaman katalog (index)
Route::get('/', [PlantController::class, 'index'])->name('home');
Route::get('/collection', [PlantController::class, 'collection'])->name('collection');

// Halaman detail tanaman (show)
Route::get('/plant/{plant:slug}', [PlantController::class, 'show'])->name('plant.show');

Route::get('/admin/login', [AdminPlantController::class, 'login'])->name('admin.login');
Route::post('/admin/login', [AdminPlantController::class, 'authenticate'])->name('admin.authenticate');

Route::prefix('admin')->name('admin.')->middleware('admin')->group(function () {
    Route::post('/logout', [AdminPlantController::class, 'logout'])->name('logout');
    
    // Route CRUD untuk Tanaman
    Route::resource('plants', AdminPlantController::class)->except(['show']);
    Route::get('/plants/{plant}/history', [AdminPlantController::class, 'history'])->name('plants.history');
    Route::post('/plants/{plant}/population', [AdminPlantController::class, 'updatePopulation'])->name('plants.population.update');
    
    // Route CRUD untuk Kategori (Tambahkan di sini)
    Route::resource('categories', AdminCategoryController::class)->except(['create', 'show', 'destroy']);
});