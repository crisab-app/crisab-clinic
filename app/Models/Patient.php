<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Patient extends Model
{
    use HasFactory, HasUuids, LogsActivity; // <-- IMPORTANTE PARA EL UUID GLOBAL Y LA BITÁCORA

    protected $fillable = [
        'name', 'email', 'curp', 'rfc', 'tax_name', 'tax_zip_code', 'tax_regime', 
        'phone', 'emergency_contact_name', 'emergency_contact_phone', 
        'date_of_birth', 'gender', 'blood_type', 'allergies'
    ];

    // --- CONFIGURACIÓN DE LA BITÁCORA DE AUDITORÍA (NOM-024) ---
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable() // Vigila todos los campos declarados en el $fillable de arriba
            ->logOnlyDirty() // Solo guarda registro si el dato REALMENTE cambió
            ->dontSubmitEmptyLogs() // No genera basura en la base de datos si no hubo cambios
            ->setDescriptionForEvent(fn(string $eventName) => "Expediente de paciente {$eventName}");
    }

    // --- RELACIONES ---

    // Relación: Un paciente pertenece a muchas clínicas (o Iglesias)
    public function clinics()
    {
        return $this->belongsToMany(Clinic::class);
    }
    
    // Relación: Un paciente tiene muchas consultas
    public function consultations()
    {
        return $this->hasMany(Consultation::class)->latest();
    }
}