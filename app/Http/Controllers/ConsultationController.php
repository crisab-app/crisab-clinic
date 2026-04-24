<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Consultation;
use Illuminate\Http\Request;

class ConsultationController extends Controller
{
    public function create(Appointment $appointment)
    {
        // Seguridad: Validar que la cita pertenezca a la clínica del usuario
        if ($appointment->clinic_id !== auth()->user()->clinic_id) {
            abort(403, 'No tienes permiso para ver esta consulta.');
        }

        $patient = $appointment->patient;

        return view('consultations.create', compact('appointment', 'patient'));
    }

    public function store(Request $request, Appointment $appointment)
    {
        // 1. Guardar la consulta en la base de datos
        $consultation = Consultation::create([
            'appointment_id' => $appointment->id,
            'patient_id' => $appointment->patient_id,
            'user_id' => auth()->id(), // El doctor actual
            'clinic_id' => auth()->user()->clinic_id,
            'vitals' => $request->vitals, // Viene como un arreglo desde el formulario
            'subjective' => $request->subjective,
            'objective' => $request->objective,
            'assessment' => $request->assessment,
            'plan' => $request->plan,
        ]);

        // 2. Cambiar el estatus de la cita para que desaparezca de "Pendientes"
        $appointment->update(['status' => 'Finalizada']);

        // 3. Redirigir de vuelta a la agenda
        return redirect()->route('appointments.index')->with('success', 'Consulta finalizada y guardada exitosamente.');
    }
}