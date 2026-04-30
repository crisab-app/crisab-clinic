<?php

namespace App\Http\Controllers;

use App\Models\Clinic;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ClinicController extends Controller
{
    public function index()
    {
        $clinics = Clinic::latest()->get();
        return view('clinics.index', compact('clinics'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $visualId = 'CLINIC-' . strtoupper(Str::random(6));

        Clinic::create([
            'name' => $request->name,
            'visual_id' => $visualId,
            'billing_plan' => 'PRO',
        ]);

        return redirect()->route('clinics.index')->with('status', 'Clínica creada con éxito.');
    }
    
    public function show($id)
    {
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
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500', 
            'billing_plan' => 'required|string|in:TRIAL,BASIC,PRO,PREMIUM,trial,basic,pro,premium',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', 
        ]);

        $clinic = Clinic::findOrFail($id);
        
        $clinic->name = $request->name;
        $clinic->phone = $request->phone;
        $clinic->address = $request->address;
        $clinic->billing_plan = strtoupper($request->billing_plan); 

        // 3. Procesamos y COMPRIMIMOS el logotipo usando PHP NATIVO (Sin librerías externas)
        if ($request->hasFile('logo')) {
            
            if ($clinic->logo_path) {
                Storage::disk('public')->delete($clinic->logo_path);
            }

            $file = $request->file('logo');
            $filename = 'logos/clinic_' . $clinic->id . '_' . time() . '.jpg';
            $realPath = $file->getRealPath();

            // Obtenemos las medidas originales de la imagen
            list($origWidth, $origHeight, $imageType) = getimagesize($realPath);
            
            // Calculamos la nueva medida (Máximo 400px de ancho)
            $maxWidth = 400;
            if ($origWidth > $maxWidth) {
                $newWidth = $maxWidth;
                $newHeight = intval($origHeight * ($maxWidth / $origWidth));
            } else {
                $newWidth = $origWidth;
                $newHeight = $origHeight;
            }

            // Creamos un "lienzo" en blanco en la memoria de PHP
            $imageTmp = imagecreatetruecolor($newWidth, $newHeight);

            // Cargamos la imagen según su formato (PNG o JPG)
            if ($imageType == IMAGETYPE_PNG) {
                $source = imagecreatefrompng($realPath);
                // Rellenar de blanco el fondo por si el PNG tiene transparencias (ya que será JPG)
                $white = imagecolorallocate($imageTmp, 255, 255, 255);
                imagefill($imageTmp, 0, 0, $white);
            } else {
                $source = imagecreatefromjpeg($realPath);
            }

            // Copiamos la imagen original al nuevo lienzo ajustando el tamaño
            imagecopyresampled($imageTmp, $source, 0, 0, 0, 0, $newWidth, $newHeight, $origWidth, $origHeight);

            // Capturamos el resultado comprimido al 75%
            ob_start();
            imagejpeg($imageTmp, null, 75);
            $imageContent = ob_get_clean();

            // Lo guardamos en el disco de Laravel
            Storage::disk('public')->put($filename, $imageContent);

            // Limpiamos la memoria del servidor
            imagedestroy($imageTmp);
            imagedestroy($source);

            // Guardamos la ruta en la base de datos
            $clinic->logo_path = $filename;
        }

        $clinic->save();

        return back()->with('status', 'Clínica actualizada correctamente.');
    }

    public function destroy($id)
    {
        $clinic = Clinic::findOrFail($id);
        
        if ($clinic->logo_path) {
            Storage::disk('public')->delete($clinic->logo_path);
        }
        
        $clinic->delete();

        return redirect()->route('clinics.index')->with('status', 'La clínica ha sido dada de baja.');
    }
}