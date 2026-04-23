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
    public function sendWhatsapp(Request $request, \App\Models\Patient $patient)
    {
        // 1. Generar un código único e irrepetible (Ej: a1b2c3d4...)
        $tokenString = bin2hex(random_bytes(16));

        // 2. Guardar el token en la base de datos asociado al paciente
        \App\Models\PatientToken::create([
            'patient_id' => $patient->id,
            'token' => $tokenString,
            'expires_at' => now()->addDays(2), // Caduca en 48 horas
        ]);

        // 3. Construir el mensaje pre-armado
        $link = route('onboarding.show', $tokenString);
        $mensaje = "¡Hola {$patient->name}! 🏥\n\nTe escribimos de la Clínica para confirmar tu próxima cita.\n\nPara agilizar tu atención al llegar, por favor ayúdanos a completar tu expediente médico seguro en el siguiente enlace (te tomará menos de 1 minuto):\n\n👉 {$link}\n\n¡Gracias y te esperamos!";

        // 4. Limpiar el número de teléfono del paciente (solo dejar números)
        $telefono = preg_replace('/[^0-9]/', '', $patient->phone);

        // Si el paciente no tiene teléfono guardado, regresamos con error
        if (empty($telefono)) {
            return back()->with('error', 'El paciente no tiene un número de teléfono registrado.');
        }

        // Si es un número local de 10 dígitos (formato México), le agregamos la clave del país (52)
        if (strlen($telefono) == 10) {
            $telefono = '52' . $telefono;
        }

        // 5. Redirigir a WhatsApp Web/App
        $url = "https://wa.me/{$telefono}?text=" . urlencode($mensaje);

        // Usamos redirect()->away() porque vamos a salir de nuestro dominio
        return redirect()->away($url);
    }
}