<?php

namespace App\Http\Controllers;

use App\Models\Empleado;
use App\Models\Departamento;
use App\Models\Rol;
use App\Models\Nomina;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmpleadoController extends Controller
{
    public function index()
    {
        $empleados = Empleado::with(['departamento', 'rol', 'nomina'])->get();
        return view('empleados.index', compact('empleados'));
    }

    public function create()
    {
        return view('empleados.form', [
            'empleado' => new Empleado(),
            'departamentos' => Departamento::all(),
            'roles' => Rol::all(),
            'nominas' => Nomina::all(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $this->validateEmpleado($request);

        $empleado = new Empleado();
        $empleado->fill($validated);

        // Auditoría
        $empleado->Creado_por = Auth::id();
        $empleado->Fecha_creacion = now();

        $empleado->Estado = 1; // Activo siempre al crear

        $empleado->save();

        return redirect()
            ->route('empleados.index')
            ->with('success', 'Empleado guardado correctamente.');
    }

    public function edit($id)
    {
        $empleado = Empleado::findOrFail($id);

        return view('empleados.form', [
            'empleado' => $empleado,
            'departamentos' => Departamento::all(),
            'roles' => Rol::all(),
            'nominas' => Nomina::all(),
        ]);
    }

    public function update(Request $request, $id)
    {
        $validated = $this->validateEmpleado($request);

        $empleado = Empleado::findOrFail($id);
        $empleado->fill($validated);

        // Auditoría
        $empleado->Actualizado_por = Auth::id();
        $empleado->Fecha_actualizacion = now();

        $empleado->save();

        return redirect()
            ->route('empleados.index')
            ->with('success', 'Empleado actualizado correctamente.');
    }

    private function validateEmpleado(Request $request)
    {
        return $request->validate([
            'id_Departamento' => 'required|integer',
            'id_Rol' => 'required|integer',

            'Estado_trabajo' => 'required|in:Activo,Inactivo',
            'Tipo_contrato' => 'required|in:Planilla,No Planilla',

            'Nombres' => 'required|string|max:255',
            'Apellido' => 'required|string|max:255',
            'Apellido2' => 'nullable|string|max:255',

            'Sexo' => 'required|in:M,F',
            'Fecha_nacimiento' => 'required|date',
            'Fecha_inicio' => 'required|date',

            'DPI' => 'required|string|max:25',
            'Numero' => 'required|string|max:20',

            'Codigo_empleado' => 'required|string|max:255',
            'id_Nomina' => 'nullable|integer',
        ]);
    }
}
