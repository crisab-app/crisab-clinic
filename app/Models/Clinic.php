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
}