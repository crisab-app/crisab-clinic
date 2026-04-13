<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Patient extends Model
{
    use HasFactory, HasUuids; // <-- IMPORTANTE PARA EL UUID GLOBAL

protected $fillable = [
        'name', 'email', 'curp', 'rfc', 'tax_name', 'tax_zip_code', 'tax_regime', 
        'phone', 'emergency_contact_name', 'emergency_contact_phone', // <-- NUEVOS
        'date_of_birth', 'gender', 'blood_type', 'allergies'
    ];

    // Relación: Un paciente pertenece a muchas clínicas (o Iglesias)
    public function clinics()
    {
        return $this->belongsToMany(Clinic::class);
    }
}