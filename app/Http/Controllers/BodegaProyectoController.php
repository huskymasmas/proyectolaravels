<?php

namespace App\Http\Controllers;

use App\Models\BodegaProyecto;
use App\Models\Proyecto;
use Illuminate\Http\Request;

class BodegaProyectoController extends Controller
{
    /**
     * Muestra todos los registros de la tabla tbl_Bodega_proyecto.
     */
    public function index(Request $request)
    {
        
        $proyectos = Proyecto::all();
        $idProyecto = $request->id_Proyecto;
        // Cargar los datos con sus relaciones (Unidad y Proyecto)
        $bodegas = BodegaProyecto::with(['unidad', 'proyecto'])
        ->when($idProyecto, function ($query) use ($idProyecto) {
            $query->whereHas('proyecto', function ($subquery) use ($idProyecto) {
                $subquery->where('id_Proyecto', $idProyecto);
            });
              })
            ->orderBy('Fecha', 'desc')
            ->get();
  
        // Retornar la vista con los datos
        return view('bodega_proyecto.index', compact('bodegas', 'proyectos', 'idProyecto'));
    }

    /**
     * Muestra un solo registro en detalle.
     */
    public function show($id)
    {
        $bodega = BodegaProyecto::with(['unidad', 'proyecto'])
            ->findOrFail($id);

        return view('bodega_proyecto.show', compact('bodega'));
    }
}
