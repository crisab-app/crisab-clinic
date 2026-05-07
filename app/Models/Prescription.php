<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prescription extends Model
{
    use HasFactory;
    protected $fillable = ['appointment_id', 'medication_id', 'dosage', 'quantity_prescribed'];

    public function appointment() { return $this->belongsTo(Appointment::class); }
    public function medication() { return $this->belongsTo(Medication::class); }
}