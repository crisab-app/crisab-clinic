<?php

namespace App\Http\Controllers\Clinic;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;

class StaffController extends Controller
{
    public function index()
    {
        // Obtenemos solo a los empleados de la clínica del usuario actual
        $staff = User::where('clinic_id', auth()->user()->clinic_id)
                     ->where('id', '!=', auth()->id()) // Excluimos al dueño de esta lista
                     ->get();

        // Módulos/Permisos disponibles (Ajusta estos nombres según tu base de datos)
        $permissions = Permission::whereIn('name', [
            'modulo_pacientes', 
            'modulo_agenda', 
            'modulo_facturacion', 
            'modulo_recursos'
        ])->get();

        return view('clinic.staff.index', compact('staff', 'permissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'permissions' => 'array'
        ]);

        // Creamos al nuevo miembro del personal
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make(Str::random(12)), // Contraseña temporal
            'clinic_id' => auth()->user()->clinic_id,
        ]);

        // Le asignamos los permisos seleccionados
        if ($request->has('permissions')) {
            $user->givePermissionTo($request->permissions);
        }

        // Aquí podrías agregar el envío de un email para que el usuario asigne su propia contraseña

        return back()->with('status', 'Personal agregado correctamente.');
    }
}