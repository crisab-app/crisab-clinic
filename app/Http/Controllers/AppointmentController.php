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
        $clinicId = auth()->user()->clinic_id;
        
        // 1. Definimos qué día estamos viendo (Por defecto, hoy)
        $date = $request->date ? Carbon::parse($request->date) : Carbon::today();

        // 2. Traemos a todos los MÉDICOS de la clínica para hacer las columnas
        $doctors = User::where('clinic_id', $clinicId)
                       ->where('member_type', 'medico')
                       ->get();

        // 3. Traemos las CITAS de ese día, incluyendo datos del paciente
        $appointments = Appointment::with(['patient', 'user'])
                                   ->where('clinic_id', $clinicId)
                                   ->whereDate('start_time', $date)
                                   ->get();

        // 4. Generamos los bloques de horas (Ej: de 8:00 AM a 8:00 PM)
        $hours = [];
        for ($i = 8; $i <= 20; $i++) {
            $hours[] = sprintf('%02d:00', $i);
        }

        return view('appointments.index', compact('date', 'doctors', 'appointments', 'hours'));
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