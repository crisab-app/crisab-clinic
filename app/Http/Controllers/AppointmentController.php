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

        // 1. Obtenemos la fecha seleccionada
        $selectedDate = $request->input('date', Carbon::today()->format('Y-m-d'));
        
        // ¡NUEVO! 2. Obtenemos la preferencia de vista (Por defecto: vertical)
        $layout = $request->input('layout', 'vertical');

        // 3. Obtener los doctores a mostrar
        if ($user->member_type === 'medico') {
            $doctors = User::where('id', $user->id)->get();
        } else {
            $doctors = User::where('clinic_id', $user->clinic_id)
                           ->where('member_type', 'medico')
                           ->get();
        }

        // 4. Obtener TODAS las citas
        $appointments = Appointment::with('patient')
            ->where('clinic_id', $user->clinic_id)
            ->whereIn('user_id', $doctors->pluck('id'))
            ->whereDate('start_time', $selectedDate)
            ->get();

        // 5. Generar la "Matriz"
        $timeSlots = [];
        $startOfDay = Carbon::createFromFormat('H:i', '08:00');
        $endOfDay = Carbon::createFromFormat('H:i', '20:00');

        while ($startOfDay <= $endOfDay) {
            $timeString = $startOfDay->format('H:i');
            $timeSlots[$timeString] = [];

            foreach ($doctors as $doctor) {
                $appt = $appointments->first(function ($item) use ($doctor, $timeString) {
                    return $item->user_id === $doctor->id && 
                           Carbon::parse($item->start_time)->format('H:i') === $timeString;
                });
                
                $timeSlots[$timeString][$doctor->id] = $appt;
            }
            $startOfDay->addMinutes(30);
        }

        // Pasamos la variable $layout a la vista
        return view('appointments.index', compact('doctors', 'selectedDate', 'timeSlots', 'layout'));
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