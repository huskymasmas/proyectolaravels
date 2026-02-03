<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BodegaParaProyectos;

class BodegaParaProyectosController extends Controller
{
    public function index(Request $request)
    {
        $proyectos = \App\Models\Proyecto::all();
        $unidades  = \App\Models\Unidad::all();

        $query = BodegaParaProyectos::with(['proyecto', 'unidades']);

        // Filtro por proyecto
        if ($request->filled('id_Proyecto')) {
            $query->where('id_Proyecto', $request->id_Proyecto);
        }

        $data = $query->get();

        return view('bodegaparaproyectos.index', compact('data', 'proyectos', 'unidades'));
    }
    public function edit($id)
{
    $item = BodegaParaProyectos::findOrFail($id);
    return response()->json($item);
}


    public function store(Request $request)
    {
        $request->validate([
            'id_Proyecto'     => 'required|integer',
            'Material'        => 'required|string',
            'id_Unidades'     => 'required|integer',
            'Cantidad_maxima' => 'required|numeric',
            'Usado'           => 'required|numeric',
            'Almazenado'      => 'required|numeric',

        ]);

        // Validaciones personalizadas
        if ($request->Cantidad_maxima < $request->Almazenado) {
            return redirect()->route('bodegaparaproyectos.index', ['id_Proyecto' => $request->id_Proyecto])
                             ->withErrors(['Almazenado' => 'La cantidad almacenada no puede ser mayor que la cantidad máxima'])
                             ->withInput();
        }

        if ($request->Cantidad_maxima < $request->Usado) {
            return redirect()->route('bodegaparaproyectos.index', ['id_Proyecto' => $request->id_Proyecto])
                             ->withErrors(['Usado' => 'El usado no puede ser mayor que la cantidad máxima'])
                             ->withInput();
        }
        BodegaParaProyectos::create($request->all());
        
        return redirect()->route('bodegaparaproyectos.index', ['id_Proyecto' => $request->id_Proyecto])
                    ->with('success', 'Registro creado correctamente.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'id_Proyecto'     => 'required|integer',
            'Material'        => 'required|string',
            'id_Unidades'     => 'required|integer',
            'Cantidad_maxima' => 'required|numeric',
            'Usado'           => 'required|numeric',
            'Almazenado'      => 'required|numeric',

        ]);
  
        if ($request->Cantidad_maxima < $request->Almazenado) {
            return redirect()->route('bodegaparaproyectos.index', ['id_Proyecto' => $request->id_Proyecto])
                             ->withErrors(['Almazenado' => 'La cantidad almacenada no puede ser mayor que la cantidad máxima'])
                             ->withInput();
        }

        if ($request->Cantidad_maxima < $request->Usado) {
            return redirect()->route('bodegaparaproyectos.index', ['id_Proyecto' => $request->id_Proyecto])
                             ->withErrors(['Usado' => 'El usado no puede ser mayor que la cantidad máxima'])
                             ->withInput();
        }

        $item = BodegaParaProyectos::findOrFail($id);
        $item->update($request->all());

        return redirect()->route('bodegaparaproyectos.index', ['id_Proyecto' => $request->id_Proyecto])
                         ->with('success', 'Registro actualizado correctamente.');
    }

    public function destroy($id)
    {
        $item = BodegaParaProyectos::findOrFail($id);
        $item->delete();

        return redirect()->route('bodegaparaproyectos.index')
                         ->with('success', 'Registro eliminado correctamente.');
    }
}
