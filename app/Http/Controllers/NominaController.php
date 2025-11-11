<?php

namespace App\Http\Controllers;

use App\Models\Nomina;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NominaController extends Controller
{
    public function index()
    {
        // 🔹 Cargar todas las nóminas
        $nominas = Nomina::orderBy('id_Nomina', 'desc')->get();
        return view('nomina.index', compact('nominas'));
    }

    public function create()
    {
        // 🔹 Crear una instancia vacía para usar en el formulario
        $nomina = new Nomina();
        return view('nomina.form', compact('nomina'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'sueldo_Base' => 'required|numeric|min:0',
            'Costo_horas_extras' => 'required|numeric|min:0',
            'Bonos' => 'nullable|numeric|min:0',
            'Bonos_adicional' => 'nullable|numeric|min:0',
            'viaticosnomina' => 'nullable|numeric|min:0',
            'Estado' => 'required|in:0,1',
        ]);

        // 🔹 Asignar campos de auditoría
        $validated['Creado_por'] = Auth::id();
        $validated['Fecha_creacion'] = now();

        Nomina::create($validated);

        return redirect()->route('nomina.index')->with('success', 'Nómina creada correctamente.');
    }

    public function edit($id)
    {
        // 🔹 Buscar nómina existente
        $nomina = Nomina::findOrFail($id);
        return view('nomina.form', compact('nomina'));
    }

    public function update(Request $request, $id)
    {
        $nomina = Nomina::findOrFail($id);

        $validated = $request->validate([
            'sueldo_Base' => 'required|numeric|min:0',
            'Costo_horas_extras' => 'required|numeric|min:0',
            'Bonos' => 'nullable|numeric|min:0',
            'Bonos_adicional' => 'nullable|numeric|min:0',
            'viaticosnomina' => 'nullable|numeric|min:0',
            'Estado' => 'required|in:0,1',
        ]);

        // 🔹 Asignar campos de auditoría
        $validated['Actualizado_por'] = Auth::id();
        $validated['Fecha_actualizacion'] = now();

        $nomina->update($validated);

        return redirect()->route('nomina.index')->with('success', 'Nómina actualizada correctamente.');
    }

    public function destroy($id)
    {
        Nomina::destroy($id);
        return redirect()->route('nomina.index')->with('success', 'Nómina eliminada correctamente.');
    }
}
