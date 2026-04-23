<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
public function index(Request $request)
    {
        // 1. Obtener la fecha seleccionada o usar 'Hoy' por defecto
        $date = $request->date ? \Carbon\Carbon::parse($request->date) : \Carbon\Carbon::today();
        
        // 2. Obtener las citas del doctor logueado para esa fecha
        $appointments = auth()->user()->clinic->appointments()
            ->whereDate('start_time', $date)
            ->when(auth()->user()->member_type === 'medico', function($query) {
                // Si es doctor, solo ve sus propias citas
                return $query->where('user_id', auth()->id());
            })
            ->with('patient')
            ->orderBy('start_time')
            ->get();

        return view('appointments.index', compact('appointments', 'date'));
    }
    public function create()
    {
        // Redirigimos al Centro de Mando
        return redirect()->route('reception.index');
    }

public function store(Request $request)
    {
        // 1. Validamos que no nos manden datos basura
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'user_id' => 'required|exists:users,id',
            'resource_id' => 'required|exists:clinic_resources,id',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time', // El fin debe ser después del inicio
        ]);

        // 2. Guardamos la cita conectada a la clínica actual
        \App\Models\Appointment::create([
            'clinic_id' => auth()->user()->clinic_id,
            'patient_id' => $request->patient_id,
            'user_id' => $request->user_id,
            'resource_id' => $request->resource_id,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'status' => 'Programada', // Estado inicial por defecto
        ]);

        // 3. ¡La pieza faltante! Redirigimos de vuelta a Recepción con un mensaje verde
        return redirect()->route('reception.index')->with('success', '¡Cita agendada exitosamente!');
    }}