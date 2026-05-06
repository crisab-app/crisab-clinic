<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

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
        // 1. Limpieza inicial. Aquí convertimos a mayúsculas o a NULL de forma definitiva.
        $request->merge([
            'curp' => $request->filled('curp') ? strtoupper($request->curp) : null,
            'rfc'  => $request->filled('rfc')  ? strtoupper($request->rfc)  : null,
            'email' => $request->filled('email') ? strtolower($request->email) : null,
        ]);

        // 2. Validación (Incluyendo los nuevos contactos de emergencia)
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
            'allergies' => 'nullable|string',
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_phone' => 'nullable|string|max:20',
        ]);

        $clinic = auth()->user()->clinic;

        // 3. Búsqueda en la Red Global (Lógica Optimizada)
        $existingPatient = null;
        
        // Solo buscamos si al menos uno de los tres datos clave viene lleno
        if ($request->filled('rfc') || $request->filled('curp') || $request->filled('email')) {
            $existingPatient = Patient::query()
                ->where(function ($q) use ($request) {
                    if ($request->filled('rfc')) $q->orWhere('rfc', $request->rfc);
                    if ($request->filled('curp')) $q->orWhere('curp', $request->curp);
                    if ($request->filled('email')) $q->orWhere('email', $request->email);
                })
                ->first();
        }

        if ($existingPatient) {
            auth()->user()->clinic->patients()->syncWithoutDetaching([$existingPatient->id]);
            return redirect()->route('patients.index')->with('success', 'Cliente/Paciente detectado en la Red Global y vinculado exitosamente.');
        }

        // 4. Creación del Paciente
        // CORRECCIÓN CLAVE: Usamos $request->curp directamente porque ya viene procesado del "merge"
        $newPatient = Patient::create([
            'name' => $request->name,
            'curp' => $request->curp, // <-- Ya no usamos strtoupper() aquí
            'rfc' => $request->rfc,   // <-- Ya no usamos strtoupper() aquí
            'tax_name' => $request->tax_name,
            'tax_zip_code' => $request->tax_zip_code,
            'tax_regime' => $request->tax_regime,
            'email' => $request->email, // <-- Ya no usamos strtolower() aquí
            'phone' => $request->phone,
            'date_of_birth' => $request->date_of_birth,
            'gender' => $request->gender,
            'blood_type' => $request->blood_type,
            'allergies' => $request->allergies,
            'emergency_contact_name' => $request->emergency_contact_name,
            'emergency_contact_phone' => $request->emergency_contact_phone,
        ]);

        // Lo vinculamos a nuestra clínica
        $clinic->patients()->attach($newPatient->id);

        return redirect()->route('patients.index')->with('success', 'Expediente creado y vinculado con éxito.');
    }

    public function destroy(Patient $patient)
    {
        auth()->user()->clinic->patients()->detach($patient->id);
        return back()->with('success', 'Paciente removido de tu lista de la clínica.');
    }

    public function show(Patient $patient)
    {
        // Lo usaremos para ver el expediente completo
    }

    public function edit(Patient $patient)
    {
        // 
    }

    public function update(Request $request, Patient $patient)
    {
        //
    }
}