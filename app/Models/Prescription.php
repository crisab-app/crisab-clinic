<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory; // <-- Importación necesaria
use Illuminate\Database\Eloquent\Model;
use App\Models\Appointment; // <-- Importación para la relación
use App\Models\Medication;  // <-- Importación para la relación

class Prescription extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'appointment_id', 
        'medication_id', 
        'dosage', 
        'quantity_prescribed'
    ];

    // Relación: Esta receta pertenece a una cita médica específica
    public function appointment() 
    { 
        return $this->belongsTo(Appointment::class); 
    }

    // Relación: Esta receta contiene un medicamento específico del catálogo
    public function medication() 
    { 
        return $this->belongsTo(Medication::class); 
    }
}