<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // 👈 Importar Auth
use App\Models\Proyecto;
use App\Models\ProyectoDetalle;

class ProyectoController extends Controller
{
    // Listar todos los proyectos
    public function index()
    {
        $proyectos = Proyecto::with('detalle')->get();
        return view('proyectos.index', compact('proyectos'));
    }

    // Mostrar formulario para crear
    public function create()
    {
        return view('proyectos.form');
    }

    // Guardar proyecto + detalle
    public function store(Request $request)
    {
        $request->validate([
            'Nombre' => 'required|string|max:255',
            'Descripcion' => 'required|string|max:255',
            'Ubicacion' => 'nullable|string|max:255',
        ]);

        // 🧩 Crear detalle del proyecto
        $detalle = ProyectoDetalle::create([
            'Descripcion' => $request->Descripcion,
            'Ubicacion' => $request->Ubicacion,
            'Estado' => 1,
            'Creado_por' => Auth::id(), // 👈 Usuario autenticado
            'Actualizado_por' => Auth::id(),
            'Fecha_creacion' => now(),
            'Fecha_actualizacion' => now()
        ]);

        // 🧩 Crear proyecto principal
        $proyecto = Proyecto::create([
            'Nombre' => $request->Nombre,
            'id_Proyecto_detalle' => $detalle->id_Proyecto_detalle,
            'Estado' => 1,
            'Creado_por' => Auth::id(), // 👈 Usuario autenticado
            'Actualizado_por' => Auth::id(),
            'Fecha_creacion' => now(),
            'Fecha_actualizacion' => now()
        ]);

        return redirect()->route('proyectos.index')->with('success', 'Proyecto creado correctamente.');
    }

    // Mostrar formulario para editar
    public function edit($id)
    {
        $proyecto = Proyecto::with('detalle')->findOrFail($id);
        return view('proyectos.form', compact('proyecto'));
    }

    // Actualizar proyecto + detalle
    public function update(Request $request, $id)
    {
        $request->validate([
            'Nombre' => 'required|string|max:255',
            'Descripcion' => 'required|string|max:255',
            'Ubicacion' => 'nullable|string|max:255',
        ]);

        $proyecto = Proyecto::findOrFail($id);
        $detalle = $proyecto->detalle;

 
        $detalle->update([
            'Descripcion' => $request->Descripcion,
            'Ubicacion' => $request->Ubicacion,
            'Actualizado_por' => Auth::id(), 
            'Fecha_actualizacion' => now()
        ]);

        // 🧩 Actualizar proyecto principal
        $proyecto->update([
            'Nombre' => $request->Nombre,
            'Actualizado_por' => Auth::id(),
            'Fecha_actualizacion' => now()
        ]);

        return redirect()->route('proyectos.index')->with('success', 'Proyecto actualizado correctamente.');
    }

    // Eliminar proyecto + detalle
    public function destroy($id)
    {
        $proyecto = Proyecto::findOrFail($id);
        $detalle = $proyecto->detalle;

        $proyecto->delete();
        $detalle->delete();

        return redirect()->route('proyectos.index')->with('success', 'Proyecto eliminado correctamente.');
    }
}
