<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\User;
use App\Models\Patient; // <-- IMPORTANTE: Importamos el modelo Patient
use Carbon\Carbon;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        // 1. Obtener la fecha seleccionada del calendario (o usar 'Hoy')
        $selectedDate = $request->input('date', Carbon::today()->format('Y-m-d'));

        // 2. Obtener los doctores a mostrar
        if ($user->member_type === 'medico') {
            // El doctor solo se ve a sí mismo en la cuadrícula
            $doctors = User::where('id', $user->id)->get();
        } else {
            // La secretaria ve a TODOS los doctores de su clínica
            $doctors = User::where('clinic_id', $user->clinic_id)
                           ->where('member_type', 'medico')
                           ->get();
        }

        // 3. Obtener TODAS las citas de esos doctores en esa fecha
        $appointments = Appointment::with('patient')
            ->where('clinic_id', $user->clinic_id)
            ->whereIn('user_id', $doctors->pluck('id')) // Buscar citas de todos los doctores de la lista
            ->whereDate('start_time', $selectedDate)
            ->get();

        // 4. Generar la "Matriz" de cuadrícula (Filas = Horas, Columnas = Doctores)
        $timeSlots = [];
        $startOfDay = Carbon::createFromFormat('H:i', '08:00');
        $endOfDay = Carbon::createFromFormat('H:i', '20:00');

        while ($startOfDay <= $endOfDay) {
            $timeString = $startOfDay->format('H:i');
            $timeSlots[$timeString] = []; // Preparamos la fila para esta hora

            // Recorremos cada doctor para ver si tiene cita en esta hora exacta
            foreach ($doctors as $doctor) {
                // Buscamos si existe una cita en la colección que coincida en doctor y hora
                $appt = $appointments->first(function ($item) use ($doctor, $timeString) {
                    return $item->user_id === $doctor->id && 
                           Carbon::parse($item->start_time)->format('H:i') === $timeString;
                });
                
                // Guardamos el resultado (la cita o nulo si está libre)
                $timeSlots[$timeString][$doctor->id] = $appt;
            }
            
            // Avanzamos 30 minutos
            $startOfDay->addMinutes(30);
        }

        return view('appointments.index', compact('doctors', 'selectedDate', 'timeSlots'));
    }

    public function create(Request $request)
    {
        $doctor_id = $request->input('doctor_id');
        $date = $request->input('date');
        $time = $request->input('time');

        $start_time = null;
        if ($date && $time) {
            $start_time = Carbon::parse("$date $time")->format('Y-m-d\TH:i'); 
        }

        // ¡AQUÍ ESTÁ LA MAGIA! Traemos la lista de todos los pacientes ordenados alfabéticamente
        $patients = Patient::orderBy('name')->get();

        return view('appointments.create', compact('doctor_id', 'start_time', 'patients'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'user_id' => 'required|exists:users,id',
            'resource_id' => 'required|exists:clinic_resources,id',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
        ]);

        Appointment::create([
            'clinic_id' => auth()->user()->clinic_id,
            'patient_id' => $request->patient_id,
            'user_id' => $request->user_id,
            'resource_id' => $request->resource_id,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'status' => 'Programada', 
        ]);

        return redirect()->route('reception.index')->with('success', '¡Cita agendada exitosamente!');
    }

    public function update(Request $request, Appointment $appointment)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'user_id' => 'required|exists:users,id',
            'resource_id' => 'required|exists:clinic_resources,id',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
        ]);

        $appointment->update([
            'patient_id' => $request->patient_id,
            'user_id' => $request->user_id,
            'resource_id' => $request->resource_id,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
        ]);

        return back()->with('success', '¡Cita reagendada/actualizada correctamente!');
    }
}