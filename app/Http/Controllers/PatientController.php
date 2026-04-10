<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;

class PatientController extends Controller
{
public function index()
    {
        // Traemos los pacientes de la clínica ordenados por el más reciente
        $patients = Patient::where('clinic_id', auth()->user()->clinic_id)
                           ->latest()
                           ->paginate(10); // Paginación automática

        return view('patients.index', compact('patients'));
    }

    public function create()
    {
        return view('patients.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|string|max:20',
            'blood_type' => 'nullable|string|max:10',
            'allergies' => 'nullable|string'
        ]);

        // Guardamos anexando el ID de la clínica
        Patient::create(array_merge($request->all(), [
            'clinic_id' => auth()->user()->clinic_id
        ]));

        return redirect()->route('patients.index')->with('success', 'Expediente del paciente creado con éxito.');
    }

    public function destroy(Patient $patient)
    {
        // Medida de seguridad: verificar que sea de esta clínica
        if ($patient->clinic_id == auth()->user()->clinic_id) {
            $patient->delete();
        }
        return back()->with('success', 'Paciente eliminado del sistema.');
    }
    /**
     * Display the specified resource.
     */
    public function show(Patient $patient)
    {
        //
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
