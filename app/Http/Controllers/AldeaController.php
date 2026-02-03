<?php

namespace App\Http\Controllers;

use App\Models\Aldea;
use App\Models\Proyecto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AldeaController extends Controller
{
    public function index()
    {
        $aldeas = Aldea::with('proyecto')->get();
        return view('aldea.index', compact('aldeas'));
    }

    public function create()
    {
        $proyectos = Proyecto::all();
        return view('aldea.form', ['aldea' => new Aldea(), 'proyectos' => $proyectos]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'Nombre' => 'required|string|max:255',
            'id_Proyecto' => 'required|integer',
            'Estado' => 'required|in:1,0'
        ]);

        $aldea = new Aldea($request->all());
        $aldea->Creado_por = Auth::id();
        $aldea->Fecha_creacion = now();
        $aldea->save();

        return redirect()->route('aldea.index')->with('success', 'Aldea creada correctamente.');
    }

    public function edit($id)
    {
        $aldea = Aldea::findOrFail($id);
        $proyectos = Proyecto::all();
        return view('aldea.form', compact('aldea', 'proyectos'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'Nombre' => 'required|string|max:255',
            'id_Proyecto' => 'required|integer',
            'Estado' => 'required|in:1,0'
        ]);

        $aldea = Aldea::findOrFail($id);
        $aldea->fill($request->all());
        $aldea->Actualizado_por = Auth::id();
        $aldea->Fecha_actualizacion = now();
        $aldea->save();

        return redirect()->route('aldea.index')->with('success', 'Aldea actualizada correctamente.');
    }

    public function destroy($id)
    {
        $aldea = Aldea::findOrFail($id);
        $aldea->delete();
        return redirect()->route('aldea.index')->with('success', 'Aldea eliminada correctamente.');
    }
}
