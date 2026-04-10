<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = [
        'clinic_id', 'name', 'email', 'phone', 'date_of_birth', 
        'gender', 'blood_type', 'allergies', 'notes'
    ];
}