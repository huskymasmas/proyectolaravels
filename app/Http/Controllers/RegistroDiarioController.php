<?php

namespace App\Http\Controllers;

use App\Models\RegistroDiario;
use App\Models\Empleado;
use App\Models\Nomina;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RegistroDiarioController extends Controller
{
    // 1️⃣ LISTAR REGISTROS
    public function index()
    {
        $registros = RegistroDiario::with(['empleado', 'nomina'])->get();
        return view('registro_diario.index', compact('registros'));
    }

    // 2️⃣ FORMULARIO DE CREACIÓN
    public function create()
    {
        $empleados = Empleado::with('nomina')->where('Estado', 1)->get();
        return view('registro_diario.form', compact('empleados'));
    }

    // 3️⃣ GUARDAR NUEVO REGISTRO
    public function store(Request $request)
    {
        $request->validate([
            'id_Empleados' => 'required|exists:tbl_Empleados,id_Empleados',
            'fecha_dia' => 'required|date',
            'dias_viaticos' => 'required|numeric|min:0',
            'Trabajo' => 'required|in:SI,NO',
            'Horas_extras' => 'nullable|numeric|min:0',
            'Adelantos' => 'nullable|numeric|min:0',
            'Pago_Parcial' => 'nullable|numeric|min:0',
        ]);

        $empleado = Empleado::with('nomina')->find($request->id_Empleados);
        $nomina = $empleado->nomina;

        // Cálculo de valores
        $viaticos = $nomina ? $nomina->Bonos : 0;
        $adelanto_viatico = $request->dias_viaticos * $viaticos;
        $sueldo_base = $nomina ? $nomina->sueldo_Base : 0;

        $total_dia = 0;
        if ($request->Trabajo == "SI") {
            $total_dia = ($sueldo_base + $viaticos) + ($request->Horas_extras * 25)
                        - $request->Adelantos - $adelanto_viatico;
        }

        RegistroDiario::create([
            'id_Empleados' => $empleado->id_Empleados,
            'id_Nomina' => $empleado->id_Nomina,
            'fecha_dia' => $request->fecha_dia,
            'dias_viaticos' => $request->dias_viaticos,
            'Adelanto_viatico' => $adelanto_viatico,
            'Trabajo' => $request->Trabajo,
            'Horas_extras' => $request->Horas_extras,
            'Adelantos' => $request->Adelantos,
            'Pago_Parcial' => $request->Pago_Parcial,
            'Total_dia' => $total_dia,
            'Creado_por' => Auth::id(),
            'Fecha_creacion' => now(),
            'Estado' => 1,
        ]);

        return redirect()->route('registro_diario.index')->with('success', 'Registro guardado correctamente');
    }

    // 🔹 PETICIÓN AJAX PARA OBTENER DATOS DEL EMPLEADO
    public function getEmpleado($id)
    {
        $empleado = Empleado::with('nomina')->find($id);
        if (!$empleado) return response()->json(['error' => 'Empleado no encontrado'], 404);

        return response()->json([
            'codigo' => $empleado->Codigo_empleado,
            'nombre' => $empleado->Nombres . ' ' . $empleado->Apellido . ' ' . $empleado->Apellido2,
            'puesto' => $empleado->id_Rol,
            'viaticos' => $empleado->nomina->Bonos ?? 0,
            'sueldo' => $empleado->nomina->sueldo_Base ?? 0,
        ]);
    }
}
