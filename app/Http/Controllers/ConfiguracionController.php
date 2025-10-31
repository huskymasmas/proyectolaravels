<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Configuracion;
use App\Models\Proyecto;

class ConfiguracionController extends Controller
{
    // Mostrar todas las configuraciones + filtro por proyecto
    public function index(Request $request)
    {
        $proyectos = Proyecto::all();
        $idProyecto = $request->get('id_Proyecto');

        $configuraciones = Configuracion::with('proyecto')
            ->when($idProyecto, function ($query) use ($idProyecto) {
                $query->where('id_Proyecto', $idProyecto);
            })
            ->get();

        return view('configuracion.index', compact('configuraciones', 'proyectos', 'idProyecto'));
    }

    // Mostrar formulario para crear
    public function create()
    {
        $proyectos = Proyecto::all();
        return view('configuracion.form', compact('proyectos'));
    }

    // Guardar nueva configuración
    public function store(Request $request)
    {
        $request->validate([
            'Parametros' => 'required|string|max:255',
            'Valor' => 'required|string|max:255',
            'NOTAS' => 'nullable|string',
            'id_Proyecto' => 'nullable|integer|exists:tbl_Proyecto,id_Proyecto'
        ]);

        Configuracion::create([
            'Parametros' => $request->Parametros,
            'Valor' => $request->Valor,
            'NOTAS' => $request->NOTAS,
            'id_Proyecto' => $request->id_Proyecto,
            'Creado_por' => Auth::id(),
            'Actualizado_por' => Auth::id(),
            'Fecha_creacion' => now(),
            'Fecha_actualizacion' => now(),
        ]);

        return redirect()->route('Configuracion.index')->with('success', 'Configuración creada correctamente.');
    }

    // Mostrar formulario para editar
    public function edit($id)
    {
        $configuracion = Configuracion::findOrFail($id);
        $proyectos = Proyecto::all();
        return view('Configuracion.form', compact('configuracion', 'proyectos'));
    }

    // Actualizar configuración
    public function update(Request $request, $id)
    {
        $request->validate([
            'Parametros' => 'required|string|max:255',
            'Valor' => 'required|string|max:255',
            'NOTAS' => 'nullable|string',
            'id_Proyecto' => 'nullable|integer|exists:tbl_Proyecto,id_Proyecto'
        ]);

        $configuracion = Configuracion::findOrFail($id);

        $configuracion->update([
            'Parametros' => $request->Parametros,
            'Valor' => $request->Valor,
            'NOTAS' => $request->NOTAS,
            'id_Proyecto' => $request->id_Proyecto,
            'Actualizado_por' => Auth::id(),
            'Fecha_actualizacion' => now(),
        ]);

        return redirect()->route('Configuracion.index')->with('success', 'Configuración actualizada correctamente.');
    }

    // Eliminar configuración
    public function destroy($id)
    {
        $configuracion = Configuracion::findOrFail($id);
        $configuracion->delete();

        return redirect()->route('Configuracion.index')->with('success', 'Configuración eliminada correctamente.');
    }
}
