<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    public function index()
    {
        // Traemos solo los pacientes VINCULADOS a esta clínica.
        $patients = auth()->user()->clinic->patients()
                                          ->latest()
                                          ->paginate(10);

        return view('patients.index', compact('patients'));
    }

    public function create()
    {
        return view('patients.create');
    }

    public function store(Request $request)
    {
        // Agregamos las reglas de validación para los datos fiscales
        $request->validate([
            'name' => 'required|string|max:255',
            'curp' => 'nullable|string|max:18',
            'rfc' => 'nullable|string|max:13', 
            'tax_name' => 'nullable|string|max:255',
            'tax_zip_code' => 'nullable|string|max:5',
            'tax_regime' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|string|max:20',
            'blood_type' => 'nullable|string|max:10',
            'allergies' => 'nullable|string'
        ]);

        $clinic = auth()->user()->clinic;

        // CAMBIO CLAVE 2: Flujo de Intercepción (Corregido)
        // Eliminamos el 'if ($request->curp)' sobrante. Ahora el query evalúa todo de golpe.
        $existingPatient = Patient::query()
            ->when($request->rfc, fn($q) => $q->where('rfc', strtoupper($request->rfc)))
            ->orWhere(fn($q) => $q->when($request->curp, fn($q2) => $q2->where('curp', strtoupper($request->curp))))
            ->orWhere(fn($q) => $q->when($request->email, fn($q2) => $q2->where('email', strtolower($request->email))))
            ->first();

        if ($existingPatient) {
            auth()->user()->clinic->patients()->syncWithoutDetaching([$existingPatient->id]);
            return redirect()->route('patients.index')->with('success', 'Cliente/Paciente detectado por RFC/CURP/Correo en la Red Global y vinculado exitosamente.');
        }

        // EL PACIENTE/EMPRESA ES NUEVO: Lo creamos globalmente con sus datos fiscales
        $newPatient = Patient::create([
            'name' => $request->name,
            'curp' => strtoupper($request->curp),
            'rfc' => strtoupper($request->rfc),
            'tax_name' => $request->tax_name,
            'tax_zip_code' => $request->tax_zip_code,
            'tax_regime' => $request->tax_regime,
            'email' => strtolower($request->email),
            'phone' => $request->phone,
            'date_of_birth' => $request->date_of_birth,
            'gender' => $request->gender,
            'blood_type' => $request->blood_type,
            'allergies' => $request->allergies,
        ]);

        // Lo vinculamos a nuestra clínica usando la tabla intermedia (clinic_patient)
        $clinic->patients()->attach($newPatient->id);

        return redirect()->route('patients.index')->with('success', 'Expediente global creado y vinculado con éxito.');
    }

    public function destroy(Patient $patient)
    {
        // Solo rompemos el vínculo (detach) entre la clínica actual y el paciente.
        auth()->user()->clinic->patients()->detach($patient->id);

        return back()->with('success', 'Paciente removido de tu lista de la clínica.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Patient $patient)
    {
        // Lo usaremos más adelante para ver el expediente completo
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Patient $patient)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Patient $patient)
    {
        //
    }
}