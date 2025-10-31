<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dosificacion;
use App\Models\Tipo_dosificador;
use App\Models\Proyecto;
use Illuminate\Support\Facades\Auth;

class DosificacionController extends Controller
{
    // Mostrar listado con filtro
    public function index(Request $request)
    {
        $tipos = Tipo_dosificador::all();
        $proyecto = proyecto::all();
        $tipoSeleccionado = $request->input('tipo');
        $proyectoSeleccionado = $request->input('proyecto');
        
        $query = Dosificacion::with('Tipo_dosificador');
        $query2 = Dosificacion::with('proyecto');

        if($proyectoSeleccionado){
            $query2->where('id_Proyecto', $proyectoSeleccionado);
        }
        if ($tipoSeleccionado) {
            $query->where('id_Tipo_dosificacion', $tipoSeleccionado);
        }

        $dosificaciones = $query->get();
        $dosificaciones_2 = $query2->get();

        return view('dosificacion.index', compact('dosificaciones', 'dosificaciones_2', 'tipos', 'tipoSeleccionado', 'proyecto' , 'proyectoSeleccionado'));
    }

    // Formulario de creación
    public function create()
    {
        $tipos = Tipo_dosificador::all();
        $proyecto = proyecto::all();
        return view('dosificacion.form', compact('tipos','proyecto' ));
      
 
    }

    // Guardar
    public function store(Request $request)
    {
        $request->validate([
            'id_Tipo_dosificacion' => 'required|exists:tbl_Tipo_dosificacion,id_Tipo_dosificacion',
            'id_Proyecto' => 'required|exists:tbl_Proyecto,id_Proyecto',
            'Cemento' => 'required|numeric',
            'Arena' => 'required|numeric',
            'Pedrin' => 'required|numeric',
            'Aditivo' => 'nullable|numeric',
            'Nota' => 'nullable|string|max:255',
        ]);

        Dosificacion::create([
            'id_Tipo_dosificacion' => $request->id_Tipo_dosificacion,
            'id_Proyecto' => $request->id_Proyecto,
            'Cemento' => $request->Cemento,
            'Arena' => $request->Arena,
            'Pedrin' => $request->Pedrin,
            'Aditivo' => $request->Aditivo,
            'Nota' => $request->Nota,
            'Estado' => 1,
            'Creado_por' => Auth::id(),
            'Actualizado_por' => Auth::id(),
            'Fecha_creacion' => now(),
            'Fecha_actualizacion' => now(),
        ]);

        return redirect()->route('dosificacion.index')->with('success', 'Dosificación creada correctamente.');
    }

    // Formulario de edición
    public function edit($id)
    {
        $dosificacion = Dosificacion::findOrFail($id);
        $tipos = Tipo_dosificador::all();
        $proyecto = proyecto::all();
        return view('dosificacion.form', compact('dosificacion', 'tipos', 'proyecto' ));
    }

    // Actualizar
    public function update(Request $request, $id)
    {
        $request->validate([
            'id_Tipo_dosificacion' => 'required|exists:tbl_Tipo_dosificacion,id_Tipo_dosificacion',
            'id_Proyecto' => 'required|exists:tbl_Proyecto,id_Proyecto',
            'Cemento' => 'required|numeric',
            'Arena' => 'required|numeric',
            'Pedrin' => 'required|numeric',
            'Aditivo' => 'nullable|numeric',
            'Nota' => 'nullable|string|max:255',
        ]);

        $dosificacion = Dosificacion::findOrFail($id);
        $dosificacion->update([
            'id_Tipo_dosificacion' => $request->id_Tipo_dosificacion,
            'id_Proyecto' => $request->id_Proyecto,
            'Cemento' => $request->Cemento,
            'Arena' => $request->Arena,
            'Pedrin' => $request->Pedrin,
            'Aditivo' => $request->Aditivo,
            'Nota' => $request->Nota,
            'Actualizado_por' => Auth::id(),
            'Fecha_actualizacion' => now(),
        ]);

        return redirect()->route('dosificacion.index')->with('success', 'Dosificación actualizada correctamente.');
    }

    // Eliminar
    public function destroy($id)
    {
        $dosificacion = Dosificacion::findOrFail($id);
        $dosificacion->delete();

        return redirect()->route('dosificacion.index')->with('success', 'Dosificación eliminada correctamente.');
    }
}
