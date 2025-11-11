<?php

namespace App\Http\Controllers;

use App\Models\DetalleNomina;
use App\Models\Empleado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DetalleNominaController extends Controller
{
    public function index(Request $request)
    {
        $empleados = Empleado::select('id_Empleados', 'Nombres', 'Apellido')->get();
        $filtro = $request->get('empleado_id');

        $detalles = DetalleNomina::with('empleado')
            ->when($filtro, fn($q) => $q->where('id_Empleados', $filtro))
            ->get();

        return view('detalle_nomina.index', compact('empleados', 'detalles', 'filtro'));
    }

    public function create()
    {
        $empleados = Empleado::select('id_Empleados', 'Nombres', 'Apellido')->get();
        return view('detalle_nomina.form', compact('empleados'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_Empleados' => 'required|exists:tbl_Empleados,id_Empleados',
            'Horas_extras' => 'required|numeric|min:0',
            'cantidad_dias' => 'required|numeric|min:1'
        ]);

        $total = DetalleNomina::calcularTotal(
            $request->id_Empleados,
            $request->Horas_extras,
            $request->cantidad_dias
        );

        DetalleNomina::create([
            'id_Empleados' => $request->id_Empleados,
            'Horas_extras' => $request->Horas_extras,
            'cantidad_dias' => $request->cantidad_dias,
            'totla_A_pagar' => $total,
            'Creado_por' => Auth::id(),
            'Actualizado_por' => Auth::id(),
            'Fecha_creacion' => now(),
            'Fecha_actualizacion' => now(),
        ]);

        return redirect()->route('detalle_nomina.index')->with('success', 'Detalle de nómina agregado correctamente.');
    }

    public function edit($id)
    {
        $detalle = DetalleNomina::findOrFail($id);
        $empleados = Empleado::select('id_Empleados', 'Nombres', 'Apellido')->get();
        return view('detalle_nomina.form', compact('detalle', 'empleados'));
    }

    public function update(Request $request, $id)
    {
        $detalle = DetalleNomina::findOrFail($id);

        $request->validate([
            'id_Empleados' => 'required|exists:tbl_Empleados,id_Empleados',
            'Horas_extras' => 'required|numeric|min:0',
            'cantidad_dias' => 'required|numeric|min:1'
        ]);

        $total = DetalleNomina::calcularTotal(
            $request->id_Empleados,
            $request->Horas_extras,
            $request->cantidad_dias
        );

        $detalle->update([
            'id_Empleados' => $request->id_Empleados,
            'Horas_extras' => $request->Horas_extras,
            'cantidad_dias' => $request->cantidad_dias,
            'totla_A_pagar' => $total,
            'Actualizado_por' => Auth::id(),
            'Fecha_actualizacion' => now(),
        ]);

        return redirect()->route('detalle_nomina.index')->with('success', 'Detalle de nómina actualizado correctamente.');
    }

    public function destroy($id)
    {
        $detalle = DetalleNomina::findOrFail($id);
        $detalle->delete();

        return redirect()->route('detalle_nomina.index')->with('success', 'Detalle de nómina eliminado correctamente.');
    }
}
