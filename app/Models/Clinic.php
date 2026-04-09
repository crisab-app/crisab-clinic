<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Clinic extends Model
{
    // Al ponerle la diagonal invertida al principio (\), le decimos a Laravel
    // exactamente dónde está la herramienta sin importar lo de arriba.
    use \Illuminate\Database\Eloquent\Concerns\HasUuids;

protected $fillable = [
        'name', 
        'visual_id', 
        'country', // <-- Permitir país
        'timezone', // <-- Permitir zona horaria
        'billing_plan',
        'phone'
    ];
// Una clínica tiene muchos recursos físicos
public function users()
{
    return $this->hasMany(User::class);
}

// Para obtener específicamente al administrador (dueño)
public function owner()
{
    return $this->hasOne(User::class)->whereHas('roles', function($q){
        $q->where('name', 'Administrador de Clinica');
    });
}
}