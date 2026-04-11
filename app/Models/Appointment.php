<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    // Relación: La cita le pertenece a un Paciente
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    // Relación: La cita le pertenece a un Médico (User)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relación: La cita ocupa un Recurso Físico (Consultorio)
    // Nota: Asumiendo que tu modelo de recursos se llama ClinicResource
    public function resource()
    {
        return $this->belongsTo(ClinicResource::class, 'resource_id');
    }

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
}