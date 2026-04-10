<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ResourceType;
use App\Models\Specialty;

class CatalogController extends Controller
{
    public function index()
    {
        // Obtenemos el ID del negocio (Cámbialo a admin_id si ya renombraste tu BD local)
        $tenantId = auth()->user()->clinic_id; 

        $resourceTypes = ResourceType::where('clinic_id', $tenantId)->get();
        $specialties = Specialty::where('clinic_id', $tenantId)->get();

        return view('catalogs.index', compact('resourceTypes', 'specialties'));
    }

    // --- MÉTODOS PARA TIPOS DE RECURSO ---
    public function storeResourceType(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);
        
        ResourceType::create([
            'clinic_id' => auth()->user()->clinic_id,
            'name' => $request->name
        ]);
        
        return back()->with('success', 'Tipo de recurso agregado correctamente.');
    }

    public function destroyResourceType($id)
    {
        ResourceType::where('clinic_id', auth()->user()->clinic_id)->findOrFail($id)->delete();
        return back()->with('success', 'Tipo de recurso eliminado.');
    }

    // --- MÉTODOS PARA ESPECIALIDADES ---
    public function storeSpecialty(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);
        
        Specialty::create([
            'clinic_id' => auth()->user()->clinic_id,
            'name' => $request->name
        ]);
        
        return back()->with('success', 'Especialidad agregada correctamente.');
    }

    public function destroySpecialty($id)
    {
        Specialty::where('clinic_id', auth()->user()->clinic_id)->findOrFail($id)->delete();
        return back()->with('success', 'Especialidad eliminada.');
    }
}