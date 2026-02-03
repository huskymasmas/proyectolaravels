<?php

namespace App\Http\Controllers;

use App\Models\Tipo_dosificador;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TipoDosificacionController extends Controller
{
    public function index()
    {
        $data = Tipo_dosificador::orderBy('id_Tipo_dosificacion', 'DESC')->get();
        return view('tipo_dosificacion.index', compact('data'));
    }

    public function create()
    {
        return view('tipo_dosificacion.form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'Nombre' => 'required|string|max:255'
        ]);

        $tipo = new Tipo_dosificador();
        $tipo->Nombre = $request->Nombre;
        $tipo->save();

        return redirect()->route('tipo_dosificacion.index')
            ->with('success', 'Registro creado correctamente.');
    }

    public function edit($id)
    {
        $item = Tipo_dosificador::findOrFail($id);
        return view('tipo_dosificacion.form', compact('item'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'Nombre' => 'required|string|max:255'
        ]);

        $item = Tipo_dosificador::findOrFail($id);
        $item->Nombre = $request->Nombre;
        $item->save();

        return redirect()->route('tipo_dosificacion.index')
            ->with('success', 'Registro actualizado correctamente.');
    }

    public function destroy($id)
    {
        $item = Tipo_dosificador::findOrFail($id);
        $item->delete();

        return redirect()->route('tipo_dosificacion.index')
            ->with('success', 'Registro eliminado.');
    }
}


