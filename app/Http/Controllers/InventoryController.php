<?php

namespace App\Http\Controllers;

use App\Models\Medication;
use App\Models\MedicationMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InventoryController extends Controller
{
    public function index()
    {
        $inventory = Medication::where('clinic_id', auth()->user()->clinic_id)
            ->orderBy('name')
            ->get();

        return view('inventory.index', compact('inventory'));
    }

    public function addStock(Request $request, Medication $medication)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string'
        ]);

        DB::transaction(function () use ($request, $medication) {
            // 1. Registrar el movimiento
            MedicationMovement::create([
                'medication_id' => $medication->id,
                'user_id' => auth()->id(),
                'type' => 'entry',
                'quantity' => $request->quantity,
                'notes' => $request->notes ?? 'Entrada de almacén'
            ]);

            // 2. Actualizar el stock actual
            $medication->increment('current_stock', $request->quantity);
        });

        return back()->with('success', "Se han añadido {$request->quantity} unidades a {$medication->name}.");
    }
    // Añade esta función a app/Http/Controllers/InventoryController.php

public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'generic_name' => 'nullable|string|max:255',
        'presentation' => 'nullable|string|max:255',
        'min_stock' => 'required|integer|min:0',
        'is_antibiotic' => 'boolean',
        'is_controlled' => 'boolean',
    ]);

    \App\Models\Medication::create([
        'clinic_id' => auth()->user()->clinic_id,
        'name' => $request->name,
        'generic_name' => $request->generic_name,
        'presentation' => $request->presentation,
        'min_stock' => $request->min_stock,
        'current_stock' => 0, // Inicia en cero, luego se añaden entradas
        'is_antibiotic' => $request->has('is_antibiotic'),
        'is_controlled' => $request->has('is_controlled'),
    ]);

    return back()->with('success', 'Producto agregado al catálogo correctamente.');
}
}