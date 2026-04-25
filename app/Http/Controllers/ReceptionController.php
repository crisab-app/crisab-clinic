<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ReceptionController extends Controller
{
    public function index(Request $request)
    {
        $clinicId = auth()->user()->clinic_id;

        // 1. Definir qué mes estamos viendo
        $date = $request->date ? Carbon::parse($request->date) : Carbon::today();
        
        // 2. Calcular inicio y fin para dibujar el calendario (Lunes a Domingo)
        $startOfMonth = $date->copy()->startOfMonth();
        $endOfMonth = $date->copy()->endOfMonth();
        
        $startCalendar = $startOfMonth->copy()->startOfWeek(Carbon::MONDAY);
        $endCalendar = $endOfMonth->copy()->endOfWeek(Carbon::SUNDAY);

        // 3. Traer todas las citas de este rango y agruparlas por día
        $appointments = Appointment::with(['patient', 'user'])
            ->where('clinic_id', $clinicId)
            ->whereBetween('start_time', [$startCalendar->startOfDay(), $endCalendar->endOfDay()])
            ->get()
            ->groupBy(function($app) {
                return Carbon::parse($app->start_time)->format('Y-m-d');
            });

        // 4. Datos para el formulario de "Cita Rápida"
        $doctors = User::where('clinic_id', $clinicId)->where('member_type', 'medico')->get();
        $patients = auth()->user()->clinic->patients; // Usamos la relación global que creamos
        // Usamos DB::table por si tu modelo de recursos se llama distinto
        $resources = DB::table('clinic_resources')->where('clinic_id', $clinicId)->get(); 

        // 5. Construir la cuadrícula de días
        $days = [];
        $currentDate = $startCalendar->copy();
        while ($currentDate <= $endCalendar) {
            $days[] = [
                'date' => $currentDate->copy(),
                'isCurrentMonth' => $currentDate->month === $date->month,
                'isToday' => $currentDate->isToday(),
                'appointments' => $appointments->get($currentDate->format('Y-m-d'), collect())
            ];
            $currentDate->addDay();
        }

        return view('reception.index', compact('date', 'days', 'doctors', 'patients', 'resources'));
    
    }
    public function index(Request $request)
    {
        $clinic = auth()->user()->clinic;

        // EL GUARDIA DE SEGURIDAD: Si el usuario no tiene clínica, lo regresamos al inicio
        if (!$clinic) {
            return redirect('/dashboard')->with('error', 'Tu usuario de Administrador aún no tiene una clínica asignada. Por favor, asígnate a una clínica desde la base de datos para acceder a Recepción.');
        }

        // Aquí sigue tu código original...
        $date = $request->date ? \Carbon\Carbon::parse($request->date) : \Carbon\Carbon::today();
        
        $startOfMonth = $date->copy()->startOfMonth()->startOfWeek();
        $endOfMonth = $date->copy()->endOfMonth()->endOfWeek();

        // Obtener citas (ahora usamos la variable $clinic de forma segura)
        $appointments = $clinic->appointments()
            ->with(['patient', 'user'])
            ->whereBetween('start_time', [$startOfMonth, $endOfMonth])
            ->get();

        // Obtener médicos y consultorios para el modal
        $doctors = \App\Models\User::where('clinic_id', $clinic->id)->where('member_type', 'medico')->get();
        $patients = $clinic->patients()->orderBy('name')->get();
        $resources = $clinic->resources()->get(); // Asumiendo que tienes esta relación

        // ... resto de tu lógica del calendario ...
        $days = [];
        for ($i = 0; $i < 42; $i++) {
            $currentDate = $startOfMonth->copy()->addDays($i);
            $days[] = [
                'date' => $currentDate,
                'isCurrentMonth' => $currentDate->month === $date->month,
                'isToday' => $currentDate->isToday(),
                'appointments' => $appointments->filter(function($app) use ($currentDate) {
                    return \Carbon\Carbon::parse($app->start_time)->isSameDay($currentDate);
                })->sortBy('start_time')
            ];
        }

        return view('reception.index', compact('days', 'date', 'patients', 'doctors', 'resources'));
    }
}