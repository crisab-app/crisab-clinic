<?php

namespace App\Http\Controllers;

use App\Models\Clinic;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Facades\Storage; // <-- Importante para guardar y borrar logotipos

class ClinicController extends Controller
{
    public function index()
    {
        // Traemos todas las clínicas ordenadas por la más reciente
        $clinics = Clinic::latest()->get();
        return view('clinics.index', compact('clinics'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // Generamos un ID visual único e identificable (Ej: CLINIC-4F8A2B)
        $visualId = 'CLINIC-' . strtoupper(Str::random(6));

        Clinic::create([
            'name' => $request->name,
            'visual_id' => $visualId,
            'billing_plan' => 'PRO', // <-- Corregido a mayúsculas para coincidir con la validación del update
        ]);

        return redirect()->route('clinics.index')->with('status', 'Clínica creada con éxito.');
    }
    
    public function show($id)
    {
        // Cargamos la clínica con su dueño para evitar consultas extra
        $clinic = Clinic::with('owner')->findOrFail($id);
        return view('clinics.show', compact('clinic'));
    }

    public function edit($id)
    {
        $clinic = Clinic::findOrFail($id);
        return view('clinics.edit', compact('clinic'));
    }

    public function update(Request $request, $id)
    {
        // 1. Validamos todos los campos, incluyendo la nueva dirección y logotipo
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500', 
            'billing_plan' => 'required|string|in:TRIAL,BASIC,PRO,PREMIUM',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Validación de imagen (Máx 2MB)
        ]);

        $clinic = Clinic::findOrFail($id); // <-- Limpiado
        
        // 2. Actualizamos los campos de texto
        $clinic->name = $request->name;
        $clinic->phone = $request->phone;
        $clinic->address = $request->address;
        $clinic->billing_plan = $request->billing_plan;

// 3. Procesamos y COMPRIMIMOS el logotipo si el usuario subió uno nuevo
        if ($request->hasFile('logo')) {
            // Si ya tenía un logo viejo, lo borramos para ahorrar espacio
            if ($clinic->logo_path) {
                Storage::disk('public')->delete($clinic->logo_path);
            }

            $file = $request->file('logo');
            
            // Creamos un nombre único y forzamos que sea JPG para mejor compresión
            $filename = 'logos/clinic_' . $clinic->id . '_' . time() . '.jpg';

            // Iniciamos el motor de imágenes
            $manager = new ImageManager(new Driver());
            $image = $manager->read($file);

            // Redimensionamos a un máximo de 400px de ancho (mantiene la proporción de alto automáticamente)
            $image->scaleDown(width: 400);

            // Comprimimos la imagen a calidad 75% y la guardamos en la bóveda pública de Laravel
            Storage::disk('public')->put($filename, (string) $image->toJpeg(75));

            // Guardamos la nueva ruta en la base de datos
            $clinic->logo_path = $filename;
        }

        // Guardamos los cambios en la base de datos
        $clinic->save();

        // NOTA DE SEGURIDAD: Como este es un controlador de Admin, redirige a la lista general.
        // Si el que editó fue el dueño de la clínica (no tú), podrías querer redirigirlo a 'dashboard'.
        return redirect()->route('clinics.index')->with('status', 'Clínica actualizada correctamente.');
    }

    public function destroy($id)
    {
        $clinic = Clinic::findOrFail($id);
        
        // Limpieza: Borrar el logotipo del servidor antes de borrar la clínica
        if ($clinic->logo_path) {
            Storage::disk('public')->delete($clinic->logo_path);
        }
        
        // Podrías usar SoftDeletes si no quieres borrar los datos permanentemente
        $clinic->delete();

        return redirect()->route('clinics.index')->with('status', 'La clínica ha sido dada de baja.');
    }
}