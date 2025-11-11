<?php

namespace App\Http\Controllers;

use App\Models\FormatoControlDespachoPlanta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FormatoControlDespachoPlantaController extends Controller
{
    public function Index(Request $request)
{
    $proyectos = \App\Models\Proyecto::all();
    $query = FormatoControlDespachoPlanta::with(['proyecto', 'empleado']);

    if ($request->has('id_Proyecto') && $request->id_Proyecto != '') {
        $query->where('id_Proyecto', $request->id_Proyecto);
    }

    $formatos = $query->get();

    return view('formato_despacho.index', compact('formatos', 'proyectos'));
}

public function edit($id)
{
    $formato = FormatoControlDespachoPlanta::with(['proyecto', 'empleado'])->findOrFail($id);
    $empleados = \App\Models\Empleado::all();

    return view('formato_despacho.form', compact('formato', 'empleados'));
}

public function update(Request $request, $id)
{
    $request->validate([
        'id_Empleados' => 'required|integer',
        'Observaciones' => 'nullable|string|max:255',
    ]);

    $formato = FormatoControlDespachoPlanta::findOrFail($id);
    $formato->id_Empleados = $request->id_Empleados;
    $formato->Observaciones = $request->Observaciones;
    $formato->Actualizado_por = Auth::id();
    $formato->Fecha_actualizacion = now();
    $formato->save();

    return redirect()->route('formato.index')->with('success', 'Formato actualizado correctamente.');
}

   

  
}
