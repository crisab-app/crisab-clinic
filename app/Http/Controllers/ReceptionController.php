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
        $clinic = auth()->user()->clinic;

        // EL GUARDIA DE SEGURIDAD: Si el usuario no tiene clínica, lo regresamos al inicio
        if (!$clinic) {
            return redirect('/dashboard')->with('error', 'Tu usuario de Administrador aún no tiene una clínica asignada. Por favor, asígnate a una clínica desde la base de datos para acceder a Recepción.');
        }

        // 1. Definir qué mes estamos viendo
        $date = $request->date ? Carbon::parse($request->date) : Carbon::today();
        
        // 2. Calcular inicio y fin para dibujar el calendario (Lunes a Domingo)
        $startOfMonth = $date->copy()->startOfMonth();
        $endOfMonth = $date->copy()->endOfMonth();
        
        $startCalendar = $startOfMonth->copy()->startOfWeek(Carbon::MONDAY);
        $endCalendar = $endOfMonth->copy()->endOfWeek(Carbon::SUNDAY);

        // 3. Traer todas las citas de este rango y agruparlas por día (Optimizado)
        $appointments = $clinic->appointments()
            ->with(['patient', 'user'])
            ->whereBetween('start_time', [$startCalendar->startOfDay(), $endCalendar->endOfDay()])
            ->get()
            ->groupBy(function($app) {
                return Carbon::parse($app->start_time)->format('Y-m-d');
            });

        // 4. Datos para el formulario de "Cita Rápida"
        $doctors = User::where('clinic_id', $clinic->id)->where('member_type', 'medico')->get();
        $patients = $clinic->patients()->orderBy('name')->get();
        
        // Usamos DB::table de forma segura por si no tienes la relación definida en el modelo Clinic
        $resources = DB::table('clinic_resources')->where('clinic_id', $clinic->id)->get(); 

        // 5. Construir la cuadrícula de días
        $days = [];
        $currentDate = $startCalendar->copy();
        
        while ($currentDate <= $endCalendar) {
            $days[] = [
                'date' => $currentDate->copy(),
                'isCurrentMonth' => $currentDate->month === $date->month,
                'isToday' => $currentDate->isToday(),
                // Extraemos las citas de ese día y las ordenamos por hora
                'appointments' => $appointments->get($currentDate->format('Y-m-d'), collect())->sortBy('start_time')
            ];
            $currentDate->addDay();
        }

        return view('reception.index', compact('date', 'days', 'doctors', 'patients', 'resources'));
    }
}