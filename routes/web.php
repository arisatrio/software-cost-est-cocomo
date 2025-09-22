<?php

use App\Http\Controllers\CocomoController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->to('/login');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::resource('cocomo', CocomoController::class)->middleware(['auth', 'verified']);

// Additional COCOMO routes for accuracy tracking
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/cocomo/{id}/actual-data', [CocomoController::class, 'actualDataForm'])->name('cocomo.actual-data-form');
    Route::patch('/cocomo/{id}/actual-data', [CocomoController::class, 'updateActual'])->name('cocomo.update-actual');
    // Route::get('/accuracy-dashboard', [CocomoController::class, 'accuracyDashboard'])->name('cocomo.accuracy-dashboard');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
