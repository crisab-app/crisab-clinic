<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Consultation extends Model
{
    use HasFactory;

    // Indicamos que el ID no es un número autoincremental, sino un UUID
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'appointment_id', 'patient_id', 'user_id', 'clinic_id',
        'vitals', 'subjective', 'objective', 'assessment', 'plan'
    ];

    // Convertimos automáticamente el JSON de signos vitales a un arreglo de PHP
    protected $casts = [
        'vitals' => 'array',
    ];

    // Generamos el UUID automáticamente al crear la consulta
    protected static function booted()
    {
        static::creating(fn ($consultation) => $consultation->id = (string) Str::uuid());
    }

    // RELACIONES
    public function patient() { return $this->belongsTo(Patient::class); }
    public function doctor() { return $this->belongsTo(User::class, 'user_id'); }
    public function appointment() { return $this->belongsTo(Appointment::class); }
}