<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientToken extends Model
{
    use HasFactory;

    // 1. Damos permiso para guardar estos campos masivamente
    protected $fillable = [
        'patient_id',
        'token',
        'expires_at',
        'is_used'
    ];

    // 2. Le decimos a Laravel que este token le pertenece a un Paciente
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}