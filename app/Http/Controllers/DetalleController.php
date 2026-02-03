<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DetalleObra;
use App\Models\Unidad;
use App\Models\Proyecto;
use Illuminate\Support\Facades\Auth;

class DetalleController extends Controller
{
    public function index(Request $request)
    {
        $detalle = DetalleObra::with('proyecto', 'unidad')->get();
        $proyectos = Proyecto::all();
        $idProyecto = $request->get('id_Proyecto');

      
        
        $detalle = DetalleObra::with('proyecto', 'unidad')
            ->when($idProyecto, function ($query) use ($idProyecto) {
                $query->where('id_Proyecto', $idProyecto);
            })
            ->get();
          
         
            $detalletotal = $detalle->sum('Resultado');

        return view('detalles.index', compact('detalle' , 'detalletotal', 'proyectos' , 'idProyecto'));
    }

    public function create()
    {
        $proyectos = Proyecto::all();
        $unidades = Unidad::all();
        return view('detalles.form', compact('proyectos', 'unidades'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_Proyecto' => 'required|exists:tbl_proyecto,id_Proyecto',
            'Tipo_Obra' => 'nullable|string|max:255',
            'Valor' => 'required|numeric',
            'id_Unidades' => 'required|exists:tbl_unidades,id_Unidades',
            'Detalle' => 'nullable|string|max:255',
            'Calculo' => 'required|numeric',
            'Descripcion' => 'nullable|string|max:255'
        ]);

        $data = [
            'id_Proyecto' => $request->id_Proyecto,
            'Valor' => $request->Valor,
            'Tipo_Obra' => $request->Tipo_Obra,
            'id_Unidades' => $request->id_Unidades,
            'Detalle' => $request->Detalle,
            'Calculo' => $request->Calculo,
            'Descripcion' => $request->Descripcion,
            'Resultado' => $request->Valor * ($request->Calculo / 100),
            'Creado_por' => Auth::id(),
            'Actualizado_por' => Auth::id(),
            'Fecha_creacion' => now(),
            'Fecha_actualizacion' => now(),
        ];
    DetalleObra::create($data);

        return redirect()->route('detalles.index')->with('success', 'Detalle registrado correctamente.');
    }

    public function destroy( $id)
    {

        $detalle = DetalleObra::findOrFail($id);
        $detalle->delete();
        return redirect()->route('detalles.index')->with('success', 'Detalle eliminado correctamente.');

    }
}
