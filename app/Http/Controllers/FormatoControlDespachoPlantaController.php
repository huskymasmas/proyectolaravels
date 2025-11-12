<?php

namespace App\Http\Controllers;

use App\Models\FormatoControlDespachoPlanta;
use App\Models\Aldea;
use App\Models\Empleado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FormatoControlDespachoPlantaController extends Controller
{
    public function index(Request $request)
    {
        $aldeas = Aldea::all();

        $query = FormatoControlDespachoPlanta::query()->where('Estado', 1);

        if ($request->filled('id_Aldea')) {
            $query->where('id_Aldea', $request->id_Aldea);
        }

        if ($request->filled('fecha_desde') && $request->filled('fecha_hasta')) {
            $query->whereBetween('Fecha', [$request->fecha_desde, $request->fecha_hasta]);
        }

        $registros = $query->with('aldea')->get();

        // Totales
        $total_mt3 = $registros->sum('Cantidad_Concreto_mT3');
        $total_granel = $registros->sum('Concreto_granel_kg');
        $total_sacos = $registros->sum('Concreto_sacos_kg');
        $total_general = $registros->sum('total');
        $total_arena = $registros->sum('kg_Arena');
        $total_agua = $registros->sum('Lts_Agua');

        return view('formato_control_despacho_planta.index', compact(
            'registros',
            'aldeas',
            'total_mt3',
            'total_granel',
            'total_sacos',
            'total_general',
            'total_arena',
            'total_agua'
        ));
    }

    public function create()
    {
        $aldeas = Aldea::all();
        $empleados = Empleado::all();
        return view('formato_control_despacho_planta.form', compact('aldeas', 'empleados'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'Fecha' => 'required|date',
            'No_envio' => 'required|integer',
            'id_Aldea' => 'required|integer',
            'Conductor' => 'required|string|max:255',
            'Tipo_de_Concreto_ps' => 'required|numeric',
            'Cantidad_Concreto_mT3' => 'required|numeric',
            'Concreto_granel_kg' => 'required|numeric',
            'Concreto_sacos_kg' => 'required|numeric',
            'total' => 'required|numeric',
            'kg_Piedrin' => 'required|numeric',
            'kg_Arena' => 'required|numeric',
            'Lts_Agua' => 'required|numeric',
            'Aditivo1' => 'nullable|string|max:255',
            'Aditivo2' => 'nullable|string|max:255',
            'Aditivo3' => 'nullable|string|max:255',
            'Aditivo4' => 'nullable|string|max:255',
            'cantidad1' => 'nullable|numeric',
            'cantidad2' => 'nullable|numeric',
            'cantidad3' => 'nullable|numeric',
            'cantidad4' => 'nullable|numeric',
            'id_Empleados' => 'required|integer',
            'Supervisor' => 'nullable|string|max:255',
            'Observaciones' => 'nullable|string|max:255',
        ]);

        $data['Estado'] = 1;
        $data['Creado_por'] = Auth::id();
        $data['Fecha_creacion'] = now();

        FormatoControlDespachoPlanta::create($data);

        return redirect()->route('formato_control_despacho_planta.index')->with('success', 'Registro creado correctamente.');
    }

    public function edit($id)
    {
        $registro = FormatoControlDespachoPlanta::findOrFail($id);
        $aldeas = Aldea::all();
        $empleados = Empleado::all();
        return view('formato_control_despacho_planta.form', compact('registro', 'aldeas', 'empleados'));
    }

    public function update(Request $request, $id)
    {
        $registro = FormatoControlDespachoPlanta::findOrFail($id);

        $registro->update($request->all() + [
            'Actualizado_por' => Auth::id(),
            'Fecha_actualizacion' => now()
        ]);

        return redirect()->route('formato_control_despacho_planta.index')->with('success', 'Registro actualizado correctamente.');
    }

    public function destroy($id)
    {
        $registro = FormatoControlDespachoPlanta::findOrFail($id);
        $registro->Estado = 0;
        $registro->save();

        return redirect()->route('formato_control_despacho_planta.index')->with('success', 'Registro eliminado correctamente.');
    }
}
