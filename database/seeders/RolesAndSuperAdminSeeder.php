<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\PermissionRegistrar;

class RolesAndSuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Limpiamos la memoria RAM (caché) de Spatie para evitar choques fantasmas
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // 2. Usamos firstOrCreate: Si el rol ya existe lo ignora, si no, lo crea. ¡Cero errores!
        $roleSuperadmin = Role::firstOrCreate(['name' => 'Superadmin']);
        Role::firstOrCreate(['name' => 'Administrador de Clinica']);
        Role::firstOrCreate(['name' => 'Medico']);
        Role::firstOrCreate(['name' => 'Recepcionista']);

        // 3. Crear o actualizar al Superadmin (lee tu .env o usa el de respaldo)
        $user = User::updateOrCreate(
            ['email' => env('SUPERADMIN_EMAIL', 'admin@adminconsul.com')], // Busca este correo
            [
                'name' => 'Administrador Principal',
                'password' => Hash::make(env('SUPERADMIN_PASSWORD', 'password123')),
                'clinic_id' => null,
            ]
        );

        // 4. Le asignamos su poder
        $user->assignRole($roleSuperadmin);
    }
}