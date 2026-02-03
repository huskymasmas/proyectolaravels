<?php

namespace App\Http\Controllers;

use App\Models\Nomina;
use App\Models\DetalleNomina;
use App\Models\Empleado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NominaUnificadaController extends Controller
{
    /**
     * 🔹 Mostrar nómina + detalle en un solo index
     */
    public function index(Request $request)
    {
        $filtroEmpleado = $request->get('empleado_id');

        // Obtener todos los detalles de nómina con su nómina y empleado
        $nominaDetalles = DetalleNomina::with(['empleado', 'nomina'])
            ->when($filtroEmpleado, fn($q) => $q->where('id_Empleados', $filtroEmpleado))
            ->orderByDesc('id_Nomina')
            ->get();

        // Obtener lista de empleados para filtro
        $empleados = Empleado::select('id_Empleados', 'Nombres', 'Apellido')->get();

        return view('nomina.index', compact('nominaDetalles', 'empleados', 'filtroEmpleado'));
    }

    /**
     * 🔹 Formulario único para crear/editar nómina y detalles
     */
    public function form($id = null)
    {
        $nomina = $id ? Nomina::findOrFail($id) : new Nomina();
        $detalles = $id ? DetalleNomina::where('id_Nomina', $id)->get() : collect();
        $empleados = Empleado::select('id_Empleados', 'Nombres', 'Apellido')->get();

        return view('nomina.form', compact('nomina', 'detalles', 'empleados'));
    }

    /**
     * 🔹 Guardar nómina y sus detalles
     */
    public function store(Request $request)
    {
        $validatedNomina = $request->validate([
            'sueldo_Base' => 'required|numeric|min:0',
            'Costo_horas_extras' => 'required|numeric|min:0',
            'Bonos' => 'nullable|numeric|min:0',
            'Bonos_adicional' => 'nullable|numeric|min:0',
            'viaticosnomina' => 'nullable|numeric|min:0',
            'Estado' => 'required|in:0,1',
        ]);

        $validatedNomina['Creado_por'] = Auth::id();
        $validatedNomina['Fecha_creacion'] = now();

        $nomina = Nomina::create($validatedNomina);

        if ($request->has('detalles')) {
            foreach ($request->detalles as $d) {
                $total = DetalleNomina::calcularTotal($d['id_Empleados'], $d['Horas_extras'], $d['cantidad_dias']);
                DetalleNomina::create([
                    'id_Empleados' => $d['id_Empleados'],
                    'Horas_extras' => $d['Horas_extras'],
                    'cantidad_dias' => $d['cantidad_dias'],
                    'totla_A_pagar' => $total, // revisar que coincida con DB
                    'id_Nomina' => $nomina->id_Nomina,
                    'Creado_por' => Auth::id(),
                    'Actualizado_por' => Auth::id(),
                    'Fecha_creacion' => now(),
                    'Fecha_actualizacion' => now(),
                ]);
            }
        }

        return redirect()->route('nomina_unificada.index')->with('success', 'Nómina y detalles guardados correctamente.');
    }

    /**
     * 🔹 Actualizar nómina y detalles
     */
    public function update(Request $request, $id)
    {
        $nomina = Nomina::findOrFail($id);

        $validatedNomina = $request->validate([
            'sueldo_Base' => 'required|numeric|min:0',
            'Costo_horas_extras' => 'required|numeric|min:0',
            'Bonos' => 'nullable|numeric|min:0',
            'Bonos_adicional' => 'nullable|numeric|min:0',
            'viaticosnomina' => 'nullable|numeric|min:0',
            'Estado' => 'required|in:0,1',
        ]);

        $validatedNomina['Actualizado_por'] = Auth::id();
        $validatedNomina['Fecha_actualizacion'] = now();

        $nomina->update($validatedNomina);

        if ($request->has('detalles')) {
            foreach ($request->detalles as $d) {
                $total = DetalleNomina::calcularTotal($d['id_Empleados'], $d['Horas_extras'], $d['cantidad_dias']);
                DetalleNomina::updateOrCreate(
                    ['id_detalle_nomina' => $d['id_detalle_nomina'] ?? null],
                    [
                        'id_Empleados' => $d['id_Empleados'],
                        'Horas_extras' => $d['Horas_extras'],
                        'cantidad_dias' => $d['cantidad_dias'],
                        'totla_A_pagar' => $total, // revisar que coincida con DB
                        'id_Nomina' => $nomina->id_Nomina,
                        'Actualizado_por' => Auth::id(),
                        'Fecha_actualizacion' => now(),
                    ]
                );
            }
        }

        return redirect()->route('nomina_unificada.index')->with('success', 'Nómina y detalles actualizados correctamente.');
    }

    /**
     * 🔹 Eliminar nómina y detalles
     */
    public function destroy($id)
    {
        $nomina = Nomina::findOrFail($id);
        DetalleNomina::where('id_Nomina', $id)->delete();
        $nomina->delete();

        return redirect()->route('nomina_unificada.index')->with('success', 'Nómina y detalles eliminados correctamente.');
    }
}
