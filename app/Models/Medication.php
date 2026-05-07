<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory; // <-- Faltaba esta línea
use Illuminate\Database\Eloquent\Model;
use App\Models\Clinic; // <-- Buena práctica para la relación

class Medication extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'clinic_id', 
        'name', 
        'generic_name', 
        'presentation', 
        'is_antibiotic', 
        'is_controlled'
    ];

    // Relación: Un medicamento pertenece al catálogo de una clínica
    public function clinic() 
    { 
        return $this->belongsTo(Clinic::class); 
    }
}