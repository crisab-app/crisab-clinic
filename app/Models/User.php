<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use App\Models\Traits\Tenantable; // <-- Importamos nuestro filtro global

#[Fillable(['name', 
            'email', 
            'password', 
            'rfc', 
            'curp',
            'phone',
            'clinic_id',
            'member_type',     // NUEVO
            'professional_id', // NUEVO
            'specialty'       ])]   // 
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasRoles; // <-- Activamos Roles y el filtro Tenantable

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

        // Saber a qué clínica pertenece este usuario
    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }

    // Traer únicamente el registro de conexión más reciente
    public function latestLogin()
    {
        return $this->hasOne(LoginLog::class)->latestOfMany();
    }
}