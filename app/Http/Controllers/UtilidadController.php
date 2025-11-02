<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Utilidad;
use App\Models\Unidad;
use App\Models\Proyecto;
use App\Models\ProyectoUtilidad;  
use Illuminate\Support\Facades\Auth;

class UtilidadController extends Controller
{
    public function index(Request $request)
    {
        $proyectos = Proyecto::all();
        $proyectoSeleccionado = $request->input('proyecto');

        $query = Utilidad::with('unidad', 'proyectos');
        if ($proyectoSeleccionado) {
            $query->whereHas('proyectos', fn($q) => $q->where('id_Proyecto', $proyectoSeleccionado));
        }

        $utilidades = $query->get();

        return view('utilidades.index', compact('utilidades', 'proyectos', 'proyectoSeleccionado'));
    }


    public function create()
    {
        $unidades = Unidad::all();
        $proyectos = Proyecto::all();
        return view('utilidades.form', compact('unidades', 'proyectos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'Valor' => 'required|numeric',
            'id_Unidades' => 'required|exists:tbl_Unidades,id_Unidades',
            'Detalle' => 'nullable|string|max:255',
            'Calculo' => 'nullable|numeric',
            'Resultado' => 'nullable|numeric',
            'Descripcion' => 'nullable|string|max:255',
            'id_Proyecto' => 'required|exists:tbl_Proyecto,id_Proyecto',
        ]);

        $utilidad = Utilidad::create([
            'Valor' => $request->Valor,
            'id_Unidades' => $request->id_Unidades,
            'Detalle' => $request->Detalle,
            'Calculo' => $request->Calculo,
            'Resultado' => $request->Calculo ? $request->Valor * ($request->Calculo / 100) : 0,
            'Descripcion' => $request->Descripcion,
            'Estado' => 1,
            'Creado_por' => Auth::id(),
            'Actualizado_por' => Auth::id(),
            'Fecha_creacion' => now(),
            'Fecha_actualizacion' => now(),
        ]);

        $utilidad->proyectos()->attach($request->id_Proyecto);

        return redirect()->route('utilidades.index')->with('success', 'Utilidad creada correctamente.');
    }

    public function edit($id)
    {
        $utilidad = Utilidad::findOrFail($id);
        $unidades = Unidad::all();
        $proyectos = Proyecto::all();
        return view('utilidades.form', compact('utilidad', 'unidades', 'proyectos'));
    }

    public function update(Request $request, $id)
    {
        $utilidad = Utilidad::findOrFail($id);
        $utilidad->update([
            'Valor' => $request->Valor,
            'id_Unidades' => $request->id_Unidades,
            'Detalle' => $request->Detalle,
            'Calculo' => $request->Calculo,
            'Resultado' => $request->Calculo ? $request->Valor * ($request->Calculo / 100) : 0,
            'Descripcion' => $request->Descripcion,
            'Actualizado_por' => Auth::id(),
            'Fecha_actualizacion' => now(),
        ]);

        $utilidad->proyectos()->sync([$request->id_Proyecto]);

        return redirect()->route('utilidades.index')->with('success', 'Utilidad actualizada correctamente.');
    }

    public function destroy($id)
    {
        $utilidad = Utilidad::findOrFail($id);
        $utilidad->delete();
        return redirect()->route('utilidades.index')->with('success', 'Utilidad eliminada correctamente.');
    }
}
