<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
}