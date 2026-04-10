<?php

namespace App\Http\Controllers;

use App\Models\ClinicResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClinicResourceController extends Controller
{
public function index()
    {
        // 1. Obtenemos los recursos físicos que ya están registrados
        $resources = \App\Models\ClinicResource::where('clinic_id', auth()->user()->clinic_id)->get();

        // 2. Obtenemos los Tipos de Recurso (El catálogo dinámico que acabamos de crear)
        $resourceTypes = \App\Models\ResourceType::where('clinic_id', auth()->user()->clinic_id)->get();

        // 3. Mandamos AMBAS variables a la vista
        return view('resources.index', compact('resources', 'resourceTypes'));
    }

    public function store(Request $request)
    {
        // 2. Validamos los datos del formulario
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:50',
            'description' => 'nullable|string',
        ]);

        // 3. Guardamos el recurso amarrado a la clínica del usuario
        ClinicResource::create([
            'clinic_id' => Auth::user()->clinic_id,
            'name' => $request->name,
            'type' => $request->type,
            'description' => $request->description,
            'is_active' => true,
        ]);

        return redirect()->route('clinic-resources.index')->with('success', 'Recurso agregado correctamente.');
    }

    public function destroy(ClinicResource $clinicResource)
    {
        // 4. Medida de seguridad: Verificar que el recurso pertenezca a la clínica del usuario
        if ($clinicResource->clinic_id === Auth::user()->clinic_id) {
            $clinicResource->delete();
        }

        return redirect()->route('clinic-resources.index')->with('success', 'Recurso eliminado.');
    }
}