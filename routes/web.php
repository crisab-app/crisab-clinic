<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClinicController;

Route::get('/', function () {
    return view('welcome');
});

// Rutas legales públicas
Route::view('/privacidad', 'privacidad')->name('privacidad');
Route::view('/terminos', 'terminos')->name('terminos');

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

// ==========================================
// ZONA EXCLUSIVA PARA EL SUPERADMIN
// ==========================================
Route::middleware(['auth', 'verified', 'role:Superadmin'])->group(function () {
    
    // Rutas para gestionar a los usuarios maestros (Dueños de clínicas)
    Route::resource('superadmin/users', App\Http\Controllers\SuperadminUserController::class)
        ->names('superadmin.users')
        ->except(['create', 'store', 'show']); // No ocupamos estas porque se registran solos por fuera
        
});

require __DIR__.'/auth.php';
