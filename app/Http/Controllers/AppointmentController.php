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
        $user = auth()->user();

        // 1. Obtener la lista de doctores de la clínica (Para el selector superior)
        if ($user->member_type === 'medico') {
            // Si el que inició sesión es un doctor, solo se ve a sí mismo
            $doctors = User::where('id', $user->id)->get();
            $selectedDoctorId = $user->id;
        } else {
            // Si es secretaria/admin, ve a todos los doctores de su clínica
            $doctors = User::where('clinic_id', $user->clinic_id)
                           ->where('member_type', 'medico')
                           ->get();
            
            // Tomamos el doctor seleccionado en el filtro, o el primero de la lista por defecto
            $selectedDoctorId = $request->input('doctor_id', $doctors->first()->id ?? null);
        }

        // 2. Obtener la fecha seleccionada del calendario (o usar 'Hoy' por defecto)
        $selectedDate = $request->input('date', Carbon::today()->format('Y-m-d'));

        // 3. Obtener las citas EXACTAS de ese doctor en esa fecha
        $appointments = Appointment::with('patient')
            ->where('clinic_id', $user->clinic_id)
            ->where('user_id', $selectedDoctorId) // 'user_id' es el doctor
            ->whereDate('start_time', $selectedDate)
            ->get()
            ->keyBy(function($item) {
                // Convertimos "2026-05-20 08:30:00" a "08:30" para que encaje en nuestra cuadrícula
                return Carbon::parse($item->start_time)->format('H:i');
            });

        // 4. Generamos los bloques de media hora (Ej: De 08:00 AM a 08:00 PM)
        $timeSlots = [];
        $startOfDay = Carbon::createFromFormat('H:i', '08:00');
        $endOfDay = Carbon::createFromFormat('H:i', '20:00'); // Cambia este '20:00' si cierran más tarde

        while ($startOfDay <= $endOfDay) {
            $timeString = $startOfDay->format('H:i');
            
            // Verificamos si en este horario exacto ya hay una cita
            $timeSlots[$timeString] = $appointments->has($timeString) ? $appointments[$timeString] : null;
            
            // Avanzamos 30 minutos para el siguiente bloque
            $startOfDay->addMinutes(30);
        }

        // 5. Enviamos todo a la vista del calendario
        return view('appointments.index', compact('doctors', 'selectedDoctorId', 'selectedDate', 'timeSlots'));
    }

    public function create(Request $request)
    {
        // Atrapamos los datos que vienen del clic en el bloque vacío (Ej: 10:30)
        $doctor_id = $request->input('doctor_id');
        $date = $request->input('date');
        $time = $request->input('time');

        // Construimos la fecha y hora de inicio correcta para HTML (Ej: "2026-05-20T10:30")
        $start_time = null;
        if ($date && $time) {
            $start_time = Carbon::parse("$date $time")->format('Y-m-d\TH:i'); 
        }

        // Retornamos la vista del formulario pre-llenada (necesitarás tener este archivo en resources/views/appointments/create.blade.php)
        return view('appointments.create', compact('doctor_id', 'start_time'));
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
        Appointment::create([
            'clinic_id' => auth()->user()->clinic_id,
            'patient_id' => $request->patient_id,
            'user_id' => $request->user_id,
            'resource_id' => $request->resource_id,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'status' => 'Programada', // Estado inicial por defecto
        ]);

        // 3. Redirigimos de vuelta a Recepción con un mensaje verde
        return redirect()->route('reception.index')->with('success', '¡Cita agendada exitosamente!');
    }

    public function update(Request $request, Appointment $appointment)
    {
        // 1. Validar los datos nuevos
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'user_id' => 'required|exists:users,id',
            'resource_id' => 'required|exists:clinic_resources,id',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
        ]);

        // 2. Actualizar la cita
        $appointment->update([
            'patient_id' => $request->patient_id,
            'user_id' => $request->user_id,
            'resource_id' => $request->resource_id,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
        ]);

        // 3. Regresar a recepción con mensaje de éxito
        return back()->with('success', '¡Cita reagendada/actualizada correctamente!');
    }
}