<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DetalleCarpeta;
use App\Models\DetalleCuenta;
use App\Models\Unidad;
use App\Models\Proyecto;
use Illuminate\Support\Facades\Auth;

class DetalleController extends Controller
{
    public function index(Request $request)
    {
        $carpetas = DetalleCarpeta::with('proyecto', 'unidad')->get();
        $cuentas  = DetalleCuenta::with('proyecto', 'unidad')->get();
        $proyectos = Proyecto::all();
        $idProyecto = $request->get('id_Proyecto');

      
        
        $carpetas = DetalleCarpeta::with('proyecto', 'unidad')
            ->when($idProyecto, function ($query) use ($idProyecto) {
                $query->where('id_Proyecto', $idProyecto);
            })
            ->get();
             $cuentas = DetalleCuenta::with('proyecto', 'unidad')
            ->when($idProyecto, function ($query) use ($idProyecto) {
                $query->where('id_Proyecto', $idProyecto);
            })
            ->get();
         
            $totalCarpeta = $carpetas->sum('Resultado');
            $totalCuenta  = $cuentas->sum('Resultado');
            $totalGeneral = $totalCarpeta + $totalCuenta;
        
            $totalCarpeta = $carpetas->sum('Resultado');
            $totalCuenta  = $cuentas->sum('Resultado');
            $totalGeneral = $totalCarpeta + $totalCuenta;
        
        
        
       

        return view('detalles.index', compact('carpetas', 'cuentas', 'totalCarpeta', 'totalCuenta', 'totalGeneral' , 'proyectos' , 'idProyecto'));
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
            'tipo' => 'required|in:carpeta,cuenta',
            'id_Proyecto' => 'required|exists:tbl_Proyecto,id_Proyecto',
            'Valor' => 'required|numeric',
            'id_Unidades' => 'required|exists:tbl_Unidades,id_Unidades',
            'Detalle' => 'nullable|string|max:255',
            'Calculo' => 'required|numeric',
        ]);

        $data = [
            'id_Proyecto' => $request->id_Proyecto,
            'Valor' => $request->Valor,
            'id_Unidades' => $request->id_Unidades,
            'Detalle' => $request->Detalle,
            'Calculo' => $request->Calculo,
            'Resultado' => $request->Valor * ($request->Calculo / 100),
            'Creado_por' => Auth::id(),
            'Actualizado_por' => Auth::id(),
            'Fecha_creacion' => now(),
            'Fecha_actualizacion' => now(),
        ];

        if ($request->tipo === 'carpeta') {
            DetalleCarpeta::create($data);
        } else {
            DetalleCuenta::create($data);
        }

        return redirect()->route('detalles.index')->with('success', 'Detalle registrado correctamente.');
    }

    public function destroy($tipo, $id)
    {
        if ($tipo === 'carpeta') {
            $detalle = DetalleCarpeta::findOrFail($id);
        } else {
            $detalle = DetalleCuenta::findOrFail($id);
        }

        $detalle->delete();

        return redirect()->route('detalles.index')->with('success', 'Detalle eliminado correctamente.');
    }
}
