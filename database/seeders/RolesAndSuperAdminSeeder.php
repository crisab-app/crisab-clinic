<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RolesAndSuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Crear los roles principales
        Role::create(['name' => 'Superadmin']); // Tú (SaaS Owner)
        Role::create(['name' => 'Administrador de Clinica']);
        Role::create(['name' => 'Medico']);
        Role::create(['name' => 'Recepcion']);

        // 2. Crear tu usuario maestro (Sin clinic_id)
        $superAdmin = User::create([
        'name' => 'Administrador Principal',
        // Lee el .env, si no hay nada, usa un valor de respaldo
        'email' => env('SUPERADMIN_EMAIL', 'admin@adminconsul.com'), 
        'password' => Hash::make(env('SUPERADMIN_PASSWORD', 'password123')), 
        'clinic_id' => null,
        ]);

        // 3. Asignarte el rol supremo
        $superAdmin->assignRole('Superadmin');
    }
}