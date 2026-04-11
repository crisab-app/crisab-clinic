<?php

use App\Http\Controllers\Auth\ProviderController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClinicController;

Route::get('/', function () {
    return view('welcome');
});

// Rutas legales públicas
Route::view('/privacidad', 'privacidad')->name('privacidad');
Route::view('/terminos', 'terminos')->name('terminos');

// Rutas de Autenticación con Google
Route::get('/auth/google/redirect', [ProviderController::class, 'redirect'])->name('google.redirect');
Route::get('/auth/google/callback', [ProviderController::class, 'callback'])->name('google.callback');
// Rutas de Autenticación con Google
Route::get('/auth/google/redirect', [ProviderController::class, 'redirect'])->name('google.redirect');
Route::get('/auth/google/callback', [ProviderController::class, 'callback'])->name('google.callback');

// Rutas para completar registro con Google
Route::get('/register/google', [App\Http\Controllers\Auth\RegisteredUserController::class, 'createGoogle'])->name('register.google');
Route::post('/register/google', [App\Http\Controllers\Auth\RegisteredUserController::class, 'storeGoogle'])->name('register.google.store');

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
    Route::get('/clinics/{id}', [App\Http\Controllers\ClinicController::class, 'show'])->name('clinics.show');

    Route::get('/clinics/{id}/edit', [App\Http\Controllers\ClinicController::class, 'edit'])->name('clinics.edit');
    Route::put('/clinics/{id}', [App\Http\Controllers\ClinicController::class, 'update'])->name('clinics.update');
    Route::delete('/clinics/{id}', [App\Http\Controllers\ClinicController::class, 'destroy'])->name('clinics.destroy');

    // Rutas de Personal
    Route::get('/staff', [App\Http\Controllers\Clinic\StaffController::class, 'index'])->name('staff.index');
    Route::post('/staff', [App\Http\Controllers\Clinic\StaffController::class, 'store'])->name('staff.store');
    Route::get('/staff/{id}/edit', [App\Http\Controllers\Clinic\StaffController::class, 'edit'])->name('staff.edit');
    Route::put('/staff/{id}', [App\Http\Controllers\Clinic\StaffController::class, 'update'])->name('staff.update');
    Route::delete('/staff/{id}', [App\Http\Controllers\Clinic\StaffController::class, 'destroy'])->name('staff.destroy');

    // Centro de Catálogos (Configuración)
    Route::get('/catalogs', [App\Http\Controllers\CatalogController::class, 'index'])->name('catalogs.index');
    
    // Acciones para Tipos de Recurso
    Route::post('/catalogs/resource-types', [App\Http\Controllers\CatalogController::class, 'storeResourceType'])->name('resource-types.store');
    Route::delete('/catalogs/resource-types/{id}', [App\Http\Controllers\CatalogController::class, 'destroyResourceType'])->name('resource-types.destroy');
    
    // Acciones para Especialidades
    Route::post('/catalogs/specialties', [App\Http\Controllers\CatalogController::class, 'storeSpecialty'])->name('specialties.store');
    Route::delete('/catalogs/specialties/{id}', [App\Http\Controllers\CatalogController::class, 'destroySpecialty'])->name('specialties.destroy');

    // Módulo de Pacientes
    Route::resource('patients', App\Http\Controllers\PatientController::class);

    // Módulo de Agenda
    Route::resource('appointments', App\Http\Controllers\AppointmentController::class);
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
