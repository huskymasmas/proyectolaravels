<?php

namespace App\Http\Controllers;

use App\Models\Empleado;
use App\Models\Rol;
use App\Models\Nomina;
use App\Models\Departamento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmpleadoController extends Controller
{
    public function index()
    {
        // traer empleados con rol y nomina para mostrar el salario asignado y detalles
        $empleados = Empleado::with('rol', 'nomina')->get();
        return view('empleados.index', compact('empleados'));
    }

    public function create()
    {
        $roles = Rol::all();
        $nominas = Nomina::all();
         $departamentos = Departamento::all();
        return view('empleados.form', compact('roles', 'nominas', 'departamentos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'Nombres' => 'required|string|max:255',
            'Apellido' => 'required|string|max:255',
            'Sexo' => 'required|in:M,F',
            'DPI' => 'required|numeric|unique:tbl_Empleados,DPI',
            'Numero' => 'required|numeric|unique:tbl_Empleados,Numero',
            'id_Rol' => 'required|exists:tbl_Rol,id_Rol',
            'id_Nomina' => 'required|exists:tbl_Nomina,id_Nomina',
            'Fecha_nacimiento' => 'nullable|date',
            'Fecha_inicio' => 'nullable|date',
            'Apellido2' => 'nullable|string|max:255',
        ]);

        // obtener la nómina seleccionada y su total_pago
        $nomina = Nomina::findOrFail($request->id_Nomina);
        $salarioAsignado = $nomina->total_pago ?? 0;

        // crear instancia y asignar manualmente (evita dependencia en $fillable)
        $empleado = new Empleado();
        $empleado->id_Departamento = $request->id_Departamento;
        $empleado->Nombres = $request->Nombres;
        $empleado->Apellido = $request->Apellido;
        $empleado->Apellido2 = $request->Apellido2 ?? null;
        $empleado->Sexo = $request->Sexo;
        $empleado->Fecha_nacimiento = $request->Fecha_nacimiento ?? null;
        $empleado->Fecha_inicio = $request->Fecha_inicio ?? null;
        $empleado->DPI = $request->DPI;
        $empleado->Numero = $request->Numero;
        $empleado->id_Rol = $request->id_Rol;
        $empleado->id_Nomina = $request->id_Nomina;
        // Estado automático activo
        $empleado->Estado = 1;
        // auditoría
        $empleado->Creado_por = Auth::id();
        $empleado->Actualizado_por = Auth::id();
        $empleado->Fecha_creacion = now();
        $empleado->Fecha_actualizacion = now();

        $empleado->save();

        return redirect()->route('empleados.index')->with('success', 'Empleado registrado correctamente.');
    }

    public function edit($id)
    {
        $empleado = Empleado::findOrFail($id);
        $roles = Rol::all();
        $nominas = Nomina::all();
        $departamentos = Departamento::all();

        return view('empleados.form', compact('empleado', 'roles', 'nominas', 'departamentos'));
    }

    public function update(Request $request, $id)
    {
        $empleado = Empleado::findOrFail($id);

        $request->validate([
            'Nombres' => 'required|string|max:255',
            'Apellido' => 'required|string|max:255',
            'Sexo' => 'required|in:M,F',
            'DPI' => 'required|numeric|unique:tbl_Empleados,DPI,' . $empleado->id_Empleados . ',id_Empleados',
            'Numero' => 'required|numeric|unique:tbl_Empleados,Numero,' . $empleado->id_Empleados . ',id_Empleados',
            'id_Rol' => 'required|exists:tbl_Rol,id_Rol',
            'id_Nomina' => 'required|exists:tbl_Nomina,id_Nomina',
            'Fecha_nacimiento' => 'nullable|date',
            'Fecha_inicio' => 'nullable|date',
            'Apellido2' => 'nullable|string|max:255',
        ]);

        $nomina = Nomina::findOrFail($request->id_Nomina);
        $salarioAsignado = $nomina->total_pago ?? 0;

        $empleado->id_Departamento = $request->id_Departamento;
        $empleado->Nombres = $request->Nombres;
        $empleado->Apellido = $request->Apellido;
        $empleado->Apellido2 = $request->Apellido2 ?? $empleado->Apellido2;
        $empleado->Sexo = $request->Sexo;
        $empleado->Fecha_nacimiento = $request->Fecha_nacimiento ?? $empleado->Fecha_nacimiento;
        $empleado->Fecha_inicio = $request->Fecha_inicio ?? $empleado->Fecha_inicio;
        $empleado->DPI = $request->DPI;
        $empleado->Numero = $request->Numero;
        $empleado->id_Rol = $request->id_Rol;
        $empleado->id_Nomina = $request->id_Nomina;
        $empleado->Actualizado_por = Auth::id();
        $empleado->Fecha_actualizacion = now();

        $empleado->save();

        return redirect()->route('empleados.index')->with('success', 'Empleado actualizado correctamente.');
    }

    public function destroy($id)
    {
        $empleado = Empleado::findOrFail($id);
        $empleado->delete();
        return redirect()->route('empleados.index')->with('success', 'Empleado eliminado correctamente.');
    }
}
