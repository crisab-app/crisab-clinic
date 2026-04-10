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
        $staff = \App\Models\User::where('clinic_id', auth()->user()->clinic_id)
                                 ->where('id', '!=', auth()->id())
                                 ->get();
                                 
        $permissions = \Spatie\Permission\Models\Permission::whereIn('name', ['modulo_pacientes', 'modulo_agenda', 'modulo_facturacion', 'modulo_recursos'])->get();

        // Buscamos el catálogo de especialidades de esta clínica
        $specialties = \App\Models\Specialty::where('clinic_id', auth()->user()->clinic_id)->get();

        return view('clinics.staff.index', compact('staff', 'permissions', 'specialties'));
    }

    public function store(Request $request)
    {
$request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'member_type' => 'required|string',
            'rfc' => 'nullable|string|max:13',
            'curp' => 'nullable|string|max:18',
            'professional_id' => 'nullable|string|max:50',
            'specialty' => 'nullable|string|max:255',
            'permissions' => 'array'
        ]);

        $user = \App\Models\User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt('password123'), // Contraseña temporal por defecto
            'clinic_id' => auth()->user()->clinic_id,
            'member_type' => $request->member_type,
            'rfc' => $request->rfc,
            'curp' => $request->curp,
            'professional_id' => $request->professional_id,
            'specialty' => $request->specialty,
        ]);
        // Le asignamos los permisos seleccionados
        if ($request->has('permissions')) {
            $user->givePermissionTo($request->permissions);
        }

        // Aquí podrías agregar el envío de un email para que el usuario asigne su propia contraseña

        return back()->with('status', 'Personal agregado correctamente.');
    }
    public function edit($id)
    {
        // Buscamos al empleado, asegurándonos de que pertenezca a la misma clínica
        $staffMember = \App\Models\User::where('clinic_id', auth()->user()->clinic_id)->findOrFail($id);
        $permissions = \Spatie\Permission\Models\Permission::whereIn('name', ['modulo_pacientes', 'modulo_agenda', 'modulo_facturacion', 'modulo_recursos'])->get();

        return view('clinics.staff.edit', compact('staffMember', 'permissions'));
    }

    public function update(Request $request, $id)
    {
        $staffMember = \App\Models\User::where('clinic_id', auth()->user()->clinic_id)->findOrFail($id);

$request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'member_type' => 'required|string',
            'rfc' => 'nullable|string|max:13',
            'curp' => 'nullable|string|max:18',
            'professional_id' => 'nullable|string|max:50',
            'specialty' => 'nullable|string|max:255',
            'permissions' => 'array'
        ]);

        $staffMember->update([
            'name' => $request->name,
            'email' => $request->email,
            'member_type' => $request->member_type,
            'rfc' => $request->rfc,
            'curp' => $request->curp,
            'professional_id' => $request->professional_id,
            'specialty' => $request->specialty,
        ]);
        // Sincronizamos los permisos (si no mandó ninguno, pasamos un arreglo vacío)
        $staffMember->syncPermissions($request->permissions ?? []);

        return redirect()->route('staff.index')->with('status', 'Accesos del personal actualizados.');
    }

    public function destroy($id)
    {
        $staffMember = \App\Models\User::where('clinic_id', auth()->user()->clinic_id)->findOrFail($id);
        $staffMember->delete();

        return redirect()->route('staff.index')->with('status', 'El empleado ha sido dado de baja del sistema.');
    }
}