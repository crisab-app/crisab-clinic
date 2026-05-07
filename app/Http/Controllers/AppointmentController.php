<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\User;
use App\Models\Patient;
use App\Models\ClinicResource; // <-- NUEVO: Importamos el modelo de Recursos/Consultorios
use Carbon\Carbon;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
public function index(Request $request)
{
    $user = auth()->user();
    $selectedDate = $request->input('date', Carbon::today()->format('Y-m-d'));
    $layout = $request->input('layout', 'vertical');
    
    // NUEVO: ¿Qué queremos ver en las columnas/filas principales?
    $viewBy = $request->input('view_by', 'doctors'); // opciones: 'doctors' o 'resources'

    // Cargamos los encabezados según la elección
    if ($viewBy === 'resources') {
        $headers = \App\Models\ClinicResource::where('clinic_id', $user->clinic_id)->get();
        $idField = 'resource_id';
    } else {
        $headers = User::where('clinic_id', $user->clinic_id)->where('member_type', 'medico')->get();
        $idField = 'user_id';
    }

    $appointments = Appointment::with(['patient', 'doctor', 'resource'])
        ->where('clinic_id', $user->clinic_id)
        ->whereDate('start_time', $selectedDate)
        ->get();

    $timeSlots = [];
    $startOfDay = Carbon::createFromFormat('H:i', '08:00');
    $endOfDay = Carbon::createFromFormat('H:i', '20:00');

    while ($startOfDay <= $endOfDay) {
        $timeString = $startOfDay->format('H:i');
        $timeSlots[$timeString] = [];

        foreach ($headers as $header) {
            $appt = $appointments->first(function ($item) use ($header, $timeString, $idField) {
                return $item->{$idField} == $header->id && 
                       Carbon::parse($item->start_time)->format('H:i') === $timeString;
            });
            $timeSlots[$timeString][$header->id] = $appt;
        }
        $startOfDay->addMinutes(30);
    }

    return view('appointments.index', compact('headers', 'selectedDate', 'timeSlots', 'layout', 'viewBy'));
}

    public function create(Request $request)
    {
        $user = auth()->user();
        $doctor_id = $request->input('doctor_id');
        $date = $request->input('date');
        $time = $request->input('time');

        $start_time = null;
        if ($date && $time) {
            $start_time = Carbon::parse("$date $time")->format('Y-m-d\TH:i'); 
        }

        // 1. Traemos la lista de todos los pacientes ordenados alfabéticamente
        $patients = Patient::orderBy('name')->get();

        // 2. NUEVO: Traemos los consultorios (recursos) de la clínica actual
        $resources = ClinicResource::where('clinic_id', $user->clinic_id)->get();

        // Enviamos todo a la vista (incluyendo los resources)
        return view('appointments.create', compact('doctor_id', 'start_time', 'patients', 'resources'));
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