<?php

namespace App\Http\Controllers;

use App\Models\PatientToken;
use Illuminate\Http\Request;

class OnboardingController extends Controller
{
    // Muestra el formulario al paciente
    public function show($token)
    {
        // Buscamos el token, verificamos que no esté usado y que no haya caducado
        $patientToken = PatientToken::where('token', $token)
            ->where('is_used', false)
            ->where('expires_at', '>', now())
            ->firstOrFail(); // Si no lo encuentra o es inválido, da error 404 automático

        $patient = $patientToken->patient;

        return view('onboarding.show', compact('patient', 'token'));
    }

    // Guarda los datos y quema el token
    public function store(Request $request, $token)
    {
        $patientToken = PatientToken::where('token', $token)
            ->where('is_used', false)
            ->where('expires_at', '>', now())
            ->firstOrFail();

        $request->validate([
            'name' => 'required|string|max:255',
            'curp' => 'nullable|string|size:18',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|string',
            'blood_type' => 'nullable|string',
            'allergies' => 'nullable|string',
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_phone' => 'nullable|string|max:20',
        ]);

        // 1. Actualizamos el expediente del paciente
        $patientToken->patient->update([
            'name' => $request->name,
            'curp' => strtoupper($request->curp),
            'date_of_birth' => $request->date_of_birth,
            'gender' => $request->gender,
            'blood_type' => $request->blood_type,
            'allergies' => $request->allergies,
            'emergency_contact_name' => $request->emergency_contact_name,
            'emergency_contact_phone' => $request->emergency_contact_phone,
        ]);

        // 2. Quemamos el link para que no se pueda volver a usar
        $patientToken->update(['is_used' => true]);

        // 3. Mostramos la pantalla de éxito
        return view('onboarding.success');
    }
}