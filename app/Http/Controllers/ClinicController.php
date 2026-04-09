<?php

namespace App\Http\Controllers;

use App\Models\Clinic;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ClinicController extends Controller
{
    public function index()
    {
        // Traemos todas las clínicas ordenadas por la más reciente
        $clinics = Clinic::latest()->get();
        return view('clinics.index', compact('clinics'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // Generamos un ID visual único e identificable (Ej: CLINIC-4F8A2B)
        $visualId = 'CLINIC-' . strtoupper(Str::random(6));

        Clinic::create([
            'name' => $request->name,
            'visual_id' => $visualId,
            'billing_plan' => 'pro',
        ]);

        return redirect()->route('clinics.index')->with('status', 'Clínica creada con éxito.');
    }
    // Busca esta función o agrégala si no la tienes
public function show($id)
{
    // Cargamos la clínica con su dueño para evitar consultas extra
    $clinic = Clinic::with('owner')->findOrFail($id);
    return view('clinics.show', compact('clinic'));
}

public function edit($id)
{
    $clinic = Clinic::findOrFail($id);
    return view('clinics.edit', compact('clinic'));
}

public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'billing_plan' => 'required|string|in:TRIAL,BASIC,PRO,PREMIUM', // Valida que solo sean estos planes
        ]);

        $clinic = \App\Models\Clinic::findOrFail($id);
        
        $clinic->update([
            'name' => $request->name,
            'phone' => $request->phone,
            'billing_plan' => $request->billing_plan,
        ]);

        return redirect()->route('clinics.index')->with('status', 'Clínica actualizada correctamente.');
    }

public function destroy($id)
{
    $clinic = Clinic::findOrFail($id);
    // Podrías usar SoftDeletes si no quieres borrar los datos permanentemente
    $clinic->delete();

    return redirect()->route('clinics.index')->with('status', 'La clínica ha sido dada de baja.');
}
}