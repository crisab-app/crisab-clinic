<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResourceType extends Model
{
    use HasFactory;

    // Permitimos que Laravel guarde estos datos
    protected $fillable = [
        'clinic_id',
        'name',
    ];

    // Relación: Un tipo de recurso pertenece a una clínica
    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }
}