<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;

class TenantScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        // Si no hay usuario logueado, no hacemos nada (ej. registro)
        if (! Auth::check()) {
            return;
        }

        $user = Auth::user();

        // Si el usuario es el Superadmin maestro, lo dejamos ver TODO (ignoramos el filtro)
        if ($user->hasRole('Superadmin')) {
            return;
        }

        // Para todos los demás (Médicos, Recepción, Dueños de clínica),
        // forzamos a que solo vean la información de SU clínica.
        if ($user->clinic_id) {
            $builder->where('clinic_id', $user->clinic_id);
        }
    }
}