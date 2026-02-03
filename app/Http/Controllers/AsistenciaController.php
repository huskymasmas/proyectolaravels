<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Asistencia;
use App\Models\Empleado;
use App\Models\Nomina;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use DB;

class AsistenciaController extends Controller
{
    // Mostrar listado de asistencias y cálculo de horas extras
    public function index(Request $request)
    {
        $empleados = Empleado::all();
        $idEmpleado = $request->input('id_Empleados');

        $asistencias = Asistencia::with('empleado')
            ->when($idEmpleado, function($q) use ($idEmpleado) {
                $q->where('id_Empleados', $idEmpleado);
            })
            ->orderBy('Fecha', 'desc')
            ->get();

        $resultado = collect();

        foreach ($asistencias as $asistencia) {
            $empleado = $asistencia->empleado;
            if (!$empleado || !$empleado->nomina) continue;

            $salarioHora = $empleado->nomina->salario_base / (48 * 4);

            if ($asistencia->Hora_ingreso && $asistencia->Hora_finalizacion) {
                $horas = Carbon::parse($asistencia->Hora_ingreso)
                    ->diffInHours(Carbon::parse($asistencia->Hora_finalizacion));

                $horas -= 1; 
                $extras = $horas > 8 ? (($salarioHora * 1.5) * ($horas - 8) ) + ($salarioHora * 8) : (($horas < 8) ? abs($horas) * $salarioHora : $salarioHora * $horas);

                $resultado->push([
                    'empleado' => $empleado->Nombres . ' ' . $empleado->Apellido,
                    'fecha' => $asistencia->Fecha,
                    'horas_totales' => $horas,
                    
                    'Ganacia_dia' => $extras > 0 ? number_format($extras, 2) : $extras
                ]);
            }
        }

        return view('asistencia.index', compact('asistencias', 'empleados', 'resultado', 'idEmpleado'));
    }


    public function create(Request $request)
    {
        $empleados = Empleado::all();
   
        return view('asistencia.form', compact('empleados'));
    }

    // Registrar entrada o salida
    public function store(Request $request)
    {
        $request->validate([
            'id_Empleados' => 'required|exists:tbl_Empleados,id_Empleados',
            'tipo' => 'required|in:entrada,salida',
        ]);

        $empleadoId = $request->id_Empleados;
        $fechaHoy = now()->toDateString();

        if ($request->tipo === 'entrada') {
            Asistencia::create([
                'id_Empleados' => $empleadoId,
                'Fecha' => $fechaHoy,
                'Hora_ingreso' => now(),
                'Estado' => 1,
                'Creado_por' => Auth::id(),
                'Actualizado_por' => Auth::id(),
                'Fecha_creacion' => now(),
                'Fecha_actualizacion' => now(),
            ]);
            return redirect()->back();
        } else {
            $asistencia = Asistencia::where('id_Empleados', $empleadoId)
                ->whereDate('Fecha', $fechaHoy)
                ->latest('id_Asistencia')
                ->first();

            if (!$asistencia) {
                return redirect()->back()->with('error', 'No se encontró una entrada previa para hoy.');
            }

            $asistencia->update([
                'Hora_finalizacion' => now(),
                'Actualizado_por' => Auth::id(),
                'Fecha_actualizacion' => now(),
            ]);

            return redirect()->back();
        }
    }
}
