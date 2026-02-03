<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BodegaGeneral;
use App\Models\Unidad;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BodegaGeneralController extends Controller
{
    // 🔹 Listar todos los registros
    public function index()
    {
        $bodegas = BodegaGeneral::with('unidad')->orderBy('id_Bodega_general', 'desc')->get();
        return view('bodega.index', compact('bodegas'));
    }

    // 🔹 Formulario para crear
    public function create()
    {
        $unidades = Unidad::all();
        return view('bodega.create', compact('unidades'));
    }

    // 🔹 Guardar nuevo registro
    public function store(Request $request)
    {
        $request->validate([
            'Nombre' => 'required|string|max:255',
            'Cantidad' => 'required|numeric',
            'id_Unidades' => 'required|exists:tbl_unidades,id_Unidades',
        ]);

        BodegaGeneral::create([
            'Nombre' => $request->Nombre,
            'Descripcion' => $request->Descripcion,
            'Cantidad' => $request->Cantidad,
            'id_Unidades' => $request->id_Unidades,
            'Estado' => $request->Estado ?? 1,
            'Creado_por' => Auth::id(),
            'Fecha_creacion' => now(),
            'Actualizado_por' => Auth::id(),
            'Fecha_actualizacion' => now(),
            'stock_minimo' => $request->stock_minimo ?? 0,
        ]);

        return redirect()->route('bodega.index')
                         ->with('success', '✅ Registro de bodega creado correctamente.');
    }

    // 🔹 Formulario para editar
    public function edit($id)
    {
        $bodega = BodegaGeneral::findOrFail($id);
        $unidades = Unidad::all();
        return view('bodega.edit', compact('bodega', 'unidades'));
    }

    // 🔹 Actualizar registro
    public function update(Request $request, $id)
    {
        $request->validate([
            'Nombre' => 'required|string|max:255',
            'Cantidad' => 'required|numeric',
            'id_Unidades' => 'required|exists:tbl_Unidades,id_Unidades',
        ]);

        $bodega = BodegaGeneral::findOrFail($id);

        $bodega->update([
            'Nombre' => $request->Nombre,
            'Descripcion' => $request->Descripcion,
            'Cantidad' => $request->Cantidad,
            'id_Unidades' => $request->id_Unidades,
            'Estado' => $request->Estado ?? $bodega->Estado,
            'Actualizado_por' => Auth::id(),
            'Fecha_actualizacion' => now(),
            'stock_minimo' => $request->stock_minimo ?? $bodega->stock_minimo,
        ]);

        return redirect()->route('bodega.index')
                         ->with('success', '✅ Registro de bodega actualizado correctamente.');
    }

    // 🔹 Eliminar registro
    public function destroy($id)
    {
        $bodega = BodegaGeneral::findOrFail($id);
        $bodega->delete();

        return redirect()->route('bodega.index')
                         ->with('success', '✅ Registro de bodega eliminado correctamente.');
    }
}
