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

        // 3. Procesamos y COMPRIMIMOS el logotipo
        if ($request->hasFile('logo')) {
            
            if ($clinic->logo_path) {
                Storage::disk('public')->delete($clinic->logo_path);
            }

            $file = $request->file('logo');
            $filename = 'logos/clinic_' . $clinic->id . '_' . time() . '.jpg';

            // Usamos las rutas absolutas de V3 (a prueba de fallos y cachés)
            $driver = new \Intervention\Image\Drivers\Gd\Driver();
            $manager = new \Intervention\Image\ImageManager($driver);
            
            // Leemos y redimensionamos
            $image = $manager->read($file->getRealPath());
            $image->scaleDown(width: 400);

            // Guardamos el JPG comprimido
            Storage::disk('public')->put($filename, (string) $image->toJpeg(75));

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