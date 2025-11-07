<?php

namespace App\Http\Controllers;

use App\Models\Trabajo;
use App\Models\Unidad;
use App\Models\Proyecto;
use App\Models\EstadoTrabajo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class TrabajoController extends Controller
{
    public function index(Request $request)
    {
    $proyectos = Proyecto::all();
    $idProyecto = $request->id_Proyecto;

         // Si se selecciona un proyecto, filtramos por ese ID
        $trabajos = Trabajo::with(['proyecto', 'estadoTrabajo', 'unidad'])
        ->when($idProyecto, function ($query) use ($idProyecto) {
            $query->whereHas('proyecto', function ($subquery) use ($idProyecto) {
                $subquery->where('id_Proyecto', $idProyecto);
            });
        })
         ->orderBy('Numero_face', 'ASC')
        ->get();

        return view('trabajo.index', compact('trabajos', 'proyectos', 'idProyecto'));
    }

    public function create()
    {
        $proyectos = Proyecto::all();
        $estados = EstadoTrabajo::all();
        $unidades = Unidad::all();
        return view('trabajo.form', compact('proyectos', 'estados', 'unidades'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_Proyecto' => 'required|integer',
            'Numero_face' => 'required|numeric',
            'Nombre_face' => 'required|string|max:255',
            'id_Estado_trabajo' => 'required|integer',
            'Cantidad' => 'required|numeric',
            'id_Unidades' => 'required|integer',
            'Estado' => 'required|in:0,1'
        ]);

        Trabajo::create([
            'id_Proyecto' => $request->id_Proyecto,
            'Numero_face' => $request->Numero_face,
            'Nombre_face' => $request->Nombre_face,
            'id_Estado_trabajo' => $request->id_Estado_trabajo,
            'Cantidad' => $request->Cantidad,
            'id_Unidades' => $request->id_Unidades,
            'Estado' => $request->Estado,
            'Creado_por' => Auth::id(),
            'Actualizado_por' => Auth::id(),
            'Fecha_creacion' => Carbon::now(),
            'Fecha_actualizacion' => Carbon::now(),
        ]);

        return redirect()->route('trabajo.index')->with('success', 'Trabajo creado correctamente.');
    }

    public function edit(Trabajo $trabajo)
    {
        $proyectos = Proyecto::all();
        $estados = EstadoTrabajo::all();
        $unidades = Unidad::all();
        return view('trabajo.form', compact('trabajo', 'proyectos', 'estados', 'unidades'));
    }

    public function update(Request $request, Trabajo $trabajo)
    {
        $request->validate([
            'id_Proyecto' => 'required|integer',
            'Numero_face' => 'required|numeric',
            'Nombre_face' => 'required|string|max:255',
            'id_Estado_trabajo' => 'required|integer',
            'Cantidad' => 'required|numeric',
            'id_Unidades' => 'required|integer',
            'Estado' => 'required|in:0,1'
        ]);

        $trabajo->update([
            'id_Proyecto' => $request->id_Proyecto,
            'Numero_face' => $request->Numero_face,
            'Nombre_face' => $request->Nombre_face,
            'id_Estado_trabajo' => $request->id_Estado_trabajo,
            'Cantidad' => $request->Cantidad,
            'id_Unidades' => $request->id_Unidades,
            'Estado' => $request->Estado,
            'Actualizado_por' => Auth::id(),
            'Fecha_actualizacion' => Carbon::now(),
        ]);

        return redirect()->route('trabajo.index')->with('success', 'Trabajo actualizado correctamente.');
    }

    public function destroy(Trabajo $trabajo)
    {
        $trabajo->delete();
        return redirect()->route('trabajo.index')->with('success', 'Trabajo eliminado correctamente.');
    }
}
