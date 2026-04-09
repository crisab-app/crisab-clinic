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
        // Buscamos la clínica por su ID
        $clinic = \App\Models\Clinic::findOrFail($id);
        
        // Retornamos la vista que acabamos de crear, pasándole los datos
        return view('clinics.show', compact('clinic'));
    }
}