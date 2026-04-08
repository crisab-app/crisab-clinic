<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClinicResource extends Model
{
    protected $fillable = [
        'clinic_id',
        'name',
        'type',
        'is_active',
        'description',
    ];

    // Un recurso pertenece a una sola clínica
    public function clinic(): BelongsTo
    {
        return $this->belongsTo(Clinic::class);
    }
}