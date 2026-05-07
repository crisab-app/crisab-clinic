<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Medication extends Model
{
    use HasFactory;
    protected $fillable = ['clinic_id', 'name', 'generic_name', 'presentation', 'is_antibiotic', 'is_controlled'];

    public function clinic() { return $this->belongsTo(Clinic::class); }
}