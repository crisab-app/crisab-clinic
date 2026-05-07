<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Consultation;
use App\Models\Medication; // <-- IMPORTANTE: Agregamos el modelo de Medicamentos
use App\Models\Prescription; // <-- IMPORTANTE: Agregamos el modelo de Recetas
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

        // NUEVO: Traemos el Catálogo de Medicamentos (Vademécum) de esta clínica
        $medications = Medication::where('clinic_id', auth()->user()->clinic_id)
                                 ->orderBy('name')
                                 ->get();

        // Pasamos todo a la vista interactiva
        return view('consultations.create', compact('appointment', 'patient', 'medications'));
    }

    public function store(Request $request, Appointment $appointment)
    {
        // (Opcional pero recomendado) Validar que los datos de la receta vengan bien
        $request->validate([
            'prescriptions' => 'nullable|array',
            'prescriptions.*.medication_id' => 'required|exists:medications,id',
            'prescriptions.*.dosage' => 'required|string',
            'prescriptions.*.quantity' => 'required|integer|min:1',
        ]);

        // 1. Guardar la consulta (SOAP y Signos Vitales)
        $consultation = Consultation::create([
            'appointment_id' => $appointment->id,
            'patient_id' => $appointment->patient_id,
            'user_id' => auth()->id(), 
            'clinic_id' => auth()->user()->clinic_id,
            'vitals' => $request->vitals, 
            'subjective' => $request->subjective,
            'objective' => $request->objective,
            'assessment' => $request->assessment,
            'plan' => $request->plan,
        ]);

        // NUEVO 2. Procesar las recetas dinámicas (si el doctor agregó medicamentos)
        if ($request->has('prescriptions') && is_array($request->prescriptions)) {
            foreach ($request->prescriptions as $item) {
                Prescription::create([
                    'appointment_id' => $appointment->id,
                    'medication_id' => $item['medication_id'],
                    'dosage' => $item['dosage'],
                    'quantity_prescribed' => $item['quantity']
                ]);
            }
        }

        // 3. Cambiar el estatus de la cita para que desaparezca de "Pendientes"
        $appointment->update(['status' => 'Finalizada']);

        // 4. Redirigir de vuelta a la agenda
        return redirect()->route('appointments.index')->with('success', 'Consulta finalizada y receta guardada exitosamente.');
    }

    public function prescription(Consultation $consultation)
    {
        // Seguridad: Verificar que la consulta pertenezca a la clínica actual
        if ($consultation->clinic_id !== auth()->user()->clinic_id) {
            abort(403, 'Acceso denegado a esta receta.');
        }

        // NUEVO: Cargamos las relaciones, incluyendo las recetas y el detalle de cada medicamento
        $consultation->load(['patient', 'doctor', 'appointment.prescriptions.medication']);
        
        // Extraer las variables
        $clinic = auth()->user()->clinic;
        $patient = $consultation->patient;
        $doctor = $consultation->doctor;

        // Generar el PDF
        $pdf = \Pdf::loadView('consultations.prescription_pdf', compact('consultation', 'clinic', 'patient', 'doctor'));

        // Formato Media Carta
        $pdf->setPaper('statement', 'portrait');
        
        return $pdf->stream('Receta_' . $patient->name . '_' . $consultation->created_at->format('Ymd') . '.pdf');
    }
}