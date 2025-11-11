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
    /**
     * Lista de empleados
     */
    public function index()
    {
        $empleados = Empleado::with(['nomina'])->get();
        return view('empleados.index', compact('empleados'));
    }

    /**
     * Formulario para crear un empleado
     */
    public function create()
    {
        $departamentos = Departamento::all();
        $roles = Rol::all();
        $nominas = Nomina::all();

         return view('empleados.form', [
        'empleado' => new Empleado(),
        'departamentos' => $departamentos,
        'roles' => $roles,
        'nominas' => $nominas
        ]);
    }

    /**
     * Guardar un nuevo empleado
     */
    public function store(Request $request)
    {
        $validated = $this->validateEmpleado($request);

       $empleado = Empleado::findOrFail($id);
    $departamentos = Departamento::all();
    $roles = Rol::all();
    $nominas = Nomina::all(); // <-- agregamos las nóminas

    return view('empleados.form', compact('empleado', 'departamentos', 'roles', 'nominas'));
    }

    /**
     * Formulario para editar empleado
     */
    public function edit($id)
    {
        $empleado = Empleado::findOrFail($id);
        $departamentos = Departamento::all();
        $roles = Rol::all();

        return view('empleados.form', compact('empleado', 'departamentos', 'roles'));
    }

    /**
     * Actualizar un empleado
     */
    public function update(Request $request, $id)
    {
        $empleado = Empleado::findOrFail($id);
        $validated = $this->validateEmpleado($request, $empleado->id_Empleados);

        $empleado->fill($validated);
        $empleado->Actualizado_por = Auth::id();
        $empleado->Fecha_actualizacion = now();
        $empleado->save();

        return redirect()->route('empleados.index')->with('success', 'Empleado actualizado correctamente.');
    }

    /**
     * Eliminar un empleado
     */
    public function destroy($id)
    {
        $empleado = Empleado::findOrFail($id);
        $empleado->delete();

        return redirect()->route('empleados.index')->with('success', 'Empleado eliminado correctamente.');
    }

    /**
     * Validaciones comunes
     */
    protected function validateEmpleado(Request $request, $id = null)
    {
        return $request->validate([
            'id_Departamento'   => 'required|integer',
            'id_Rol'            => 'required|integer',
            'Nombres'           => 'required|string|max:255',
            'Apellido'          => 'required|string|max:255',
            'Apellido2'         => 'nullable|string|max:255',
            'Sexo'              => 'required|in:M,F',
            'Fecha_nacimiento'  => 'required|date',
            'Fecha_inicio'      => 'required|date',
            'DPI'               => 'required|string|max:20|unique:tbl_Empleados,DPI,' . $id . ',id_Empleados',
            'Numero'            => 'required|string|max:20',
            'Estado_trabajo'    => 'required|in:Activo,Inactivo',
            'Tipo_contrato'     => 'required|in:Planilla,No Planilla',
            'Codigo_empleado'   => 'required|string|max:50|unique:tbl_Empleados,Codigo_empleado,' . $id . ',id_Empleados',
            'id_Nomina'         => 'nullable|integer',
        ]);
    }
}
