<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class SuperadminUserController extends Controller
{
    // 1. Mostrar la lista de dueños con su última conexión
    public function index()
    {
        // Traemos a los dueños, incluyendo su clínica y su último log de acceso
        $users = User::role('Administrador de Clinica')
                    ->with(['clinic', 'latestLogin'])
                    ->get();
                    
        return view('superadmin.users.index', compact('users'));
    }

    // 2. Mostrar el formulario para corregir errores
    public function edit(User $user)
    {
        return view('superadmin.users.edit', compact('user'));
    }

    // 3. Guardar las correcciones en la base de datos
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            // El unique ignora el ID del usuario actual para que no marque error si no cambia el correo
            'email' => 'required|email|unique:users,email,' . $user->id, 
            'phone' => 'required|string|max:20',
        ]);

        $user->update($request->only('name', 'email', 'phone'));

        return redirect()->route('superadmin.users.index')->with('success', 'Datos del cliente corregidos correctamente.');
    }

    // 4. Dar de baja (Eliminar) al usuario maestro
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('superadmin.users.index')->with('success', 'Usuario dado de baja del sistema.');
    }
}