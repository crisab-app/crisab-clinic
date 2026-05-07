<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class PrescriptionController extends Controller
{
    public function printPdf($appointmentId)
    {
        // 1. Buscamos la cita con todas sus relaciones clave
        $appointment = Appointment::with([
            'patient', 
            'doctor', 
            'clinic', 
            'prescriptions.medication' // ¡Traemos las medicinas recetadas!
        ])->findOrFail($appointmentId);

        // 2. Seguridad: Solo pueden imprimir recetas de su propia clínica
        if ($appointment->clinic_id !== auth()->user()->clinic_id) {
            abort(403);
        }

        // 3. Cargamos la vista de la receta (¡aquí usaremos DOMPDF!)
        // Ojo: loadView asume que crearás un archivo en resources/views/pdf/prescription.blade.php
        $pdf = Pdf::loadView('pdf.prescription', compact('appointment'));

        // 4. Devolvemos el PDF para que se abra en el navegador listo para imprimir
        return $pdf->stream("receta_{$appointment->patient->name}_{$appointment->start_time}.pdf");
    }
}