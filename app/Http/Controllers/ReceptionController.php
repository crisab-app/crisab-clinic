<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\User;
use App\Models\ClinicResource; // <-- Agregado para consistencia
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

        // 3. Traer todas las citas de este rango y agruparlas por día
        // ¡CORRECCIÓN AQUÍ: Cambiamos 'user' por 'doctor' e incluimos 'resource'!
        $appointments = $clinic->appointments()
            ->with(['patient', 'doctor', 'resource'])
            ->whereBetween('start_time', [$startCalendar->startOfDay(), $endCalendar->endOfDay()])
            ->get()
            ->groupBy(function($app) {
                return Carbon::parse($app->start_time)->format('Y-m-d');
            });

        // 4. Datos para el formulario de "Cita Rápida"
        $doctors = User::where('clinic_id', $clinic->id)->where('member_type', 'medico')->get();
        $patients = $clinic->patients()->orderBy('name')->get();
        
        // Actualizado para usar el Modelo directamente
        $resources = ClinicResource::where('clinic_id', $clinic->id)->get(); 

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

    public function sendWhatsapp($id)
    {
        // 1. Buscamos al paciente en la base de datos
        $patient = \App\Models\Patient::findOrFail($id);

        // 2. Verificamos que realmente tenga un número guardado
        if (!$patient->phone) {
            return back()->with('error', 'El paciente no tiene un número de teléfono registrado.');
        }

        // 3. Limpiamos el número: quitamos espacios, guiones y el signo de +
        $phone = preg_replace('/[^0-9]/', '', $patient->phone);

        // (Opcional) Si el número tiene 10 dígitos, le agregamos el código de México (52)
        if (strlen($phone) == 10) {
            $phone = '52' . $phone;
        }

        // 4. Armamos el mensaje de bienvenida
        $clinicName = auth()->user()->clinic->name ?? 'nuestra clínica';
        
        $message = "Hola {$patient->name}, te damos la bienvenida a {$clinicName}. Guardamos este número como tu contacto oficial para confirmación de citas y seguimiento. ¡Quedamos a tus órdenes!";

        // 5. Codificamos el mensaje para que funcione en una URL
        $encodedMessage = urlencode($message);

        // 6. Generamos el enlace oficial de la API de WhatsApp
        $whatsappUrl = "https://wa.me/{$phone}?text={$encodedMessage}";

        // 7. Redirigimos al navegador hacia WhatsApp
        return redirect()->away($whatsappUrl);
    }
}