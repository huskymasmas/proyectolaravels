<?php

namespace App\Http\Controllers;

use App\Models\EstadoTrabajo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class EstadoTrabajoController extends Controller
{
    public function index()
    {
        $estados = EstadoTrabajo::all();
        return view('estado_trabajo.index', compact('estados'));
    }

    public function create()
    {
        return view('estado_trabajo.form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'Nombre' => 'required|string|max:255',
            'Estado' => 'required|in:0,1'
        ]);

        EstadoTrabajo::create([
            'Nombre' => $request->Nombre,
            'Estado' => $request->Estado,
            'Creado_por' => Auth::id(),
            'Actualizado_por' => Auth::id(),
            'Fecha_creacion' => Carbon::now(),
            'Fecha_actualizacion' => Carbon::now(),
        ]);

        return redirect()->route('estado_trabajo.index')->with('success', 'Estado creado correctamente.');
    }

    public function edit(EstadoTrabajo $estadoTrabajo)
    {
        return view('estado_trabajo.form', compact('estadoTrabajo'));
    }

    public function update(Request $request, EstadoTrabajo $estadoTrabajo)
    {
        $request->validate([
            'Nombre' => 'required|string|max:255',
            'Estado' => 'required|in:0,1'
        ]);

        $estadoTrabajo->update([
            'Nombre' => $request->Nombre,
            'Estado' => $request->Estado,
            'Actualizado_por' => Auth::id(),
            'Fecha_actualizacion' => Carbon::now(),
        ]);

        return redirect()->route('estado_trabajo.index')->with('success', 'Estado actualizado correctamente.');
    }

    public function destroy(EstadoTrabajo $estadoTrabajo)
    {
        $estadoTrabajo->delete();
        return redirect()->route('estado_trabajo.index')->with('success', 'Estado eliminado correctamente.');
    }
}
