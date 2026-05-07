<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\ClinicResource;
use App\Models\Patient;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'clinic_id',
        'patient_id',
        'user_id',
        'resource_id',
        'start_time',
        'end_time',
        'status',
        'reason'
    ];

    // --- RELACIONES ---

    // Relación: La cita le pertenece a un Paciente
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    // Relación: Para saber quién es el Doctor (apunta a la tabla users usando user_id)
    public function doctor()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relación: La cita ocupa un Recurso Físico (Consultorio)
    public function resource()
    {
        return $this->belongsTo(ClinicResource::class, 'resource_id');
    }

    // --- LÓGICA DE NEGOCIO ---

    // Función para detectar si el médico o el consultorio ya están ocupados
    public static function isSlotAvailable($resourceId, $userId, $start, $end)
    {
        return !self::where(function ($query) use ($resourceId, $userId, $start, $end) {
            // Buscamos si ESTE consultorio o ESTE médico...
            $query->where(function ($q) use ($resourceId, $userId) {
                $q->where('resource_id', $resourceId)
                  ->orWhere('user_id', $userId);
            })
            // ...tienen un cruce en ESTE rango de horas
            ->where(function ($q) use ($start, $end) {
                $q->whereBetween('start_time', [$start, $end])
                  ->orWhereBetween('end_time', [$start, $end]);
            });
        })->exists();
    }
    // Relación: Una cita médica genera muchas prescripciones (recetas)
    public function prescriptions()
    {
        return $this->hasMany(Prescription::class);
    }
}