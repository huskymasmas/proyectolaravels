<?php

namespace App\Http\Controllers;

use App\Models\EstacionBodega;

class EstacionBodegaController extends Controller
{
    // Mostrar listado de materiales en estación
    public function index()
    {
        // Trae los registros y la unidad relacionada
        $data = EstacionBodega::with('unidad')->get();

        return view('estacionbodega.index', compact('data'));
    }

    // Mostrar solo un registro (vista detallada opcional)
    public function show($id)
    {
        $item = EstacionBodega::with('unidad')->findOrFail($id);

        return view('estacionbodega.show', compact('item'));
    }
}
