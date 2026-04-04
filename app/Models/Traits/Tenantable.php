<?php

namespace App\Models\Traits;

use App\Models\Scopes\TenantScope;
use App\Models\Clinic;

trait Tenantable
{
    protected static function bootTenantable()
    {
        // Aplica el filtro global automáticamente
        static::addGlobalScope(new TenantScope);

        // Cuando se cree un nuevo registro (ej. un Paciente), 
        // le asigna automáticamente el clinic_id del usuario que lo está creando.
        static::creating(function ($model) {
            if (auth()->check() && ! auth()->user()->hasRole('Superadmin')) {
                $model->clinic_id = auth()->user()->clinic_id;
            }
        });
    }

    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }
}