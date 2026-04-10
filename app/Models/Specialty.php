<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Specialty extends Model
{
   protected $fillable = [
        'clinic_id', 
        'name', 
        'requires_cedula' // <-- NUEVO
    ];
}
