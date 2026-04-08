<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClinicController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Rutas para los Recursos Físicos de la Clínica
    Route::resource('clinic-resources', App\Http\Controllers\ClinicResourceController::class)
        ->only(['index', 'store', 'destroy']);

    // Módulo de Clínicas (Superadmin)
    Route::get('/clinics', [ClinicController::class, 'index'])->name('clinics.index');
    Route::post('/clinics', [ClinicController::class, 'store'])->name('clinics.store');
});

require __DIR__.'/auth.php';
