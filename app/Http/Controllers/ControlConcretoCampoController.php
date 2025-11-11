<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ControlConcretoCampo;
use App\Models\Proyecto;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ControlConcretoCampoController extends Controller
{
    public function index()
    {
        $controles = ControlConcretoCampo::with('proyecto')->orderBy('id_control_concreto_campo','desc')->get();
        return view('control_concreto_campo.index', compact('controles'));
    }

    public function create()
    {
        $proyectos = Proyecto::all();
        return view('control_concreto_campo.form', compact('proyectos'));
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $data['Creado_por'] = Auth::id();
        $data['Fecha_creacion'] = now();
        $data['Estado'] = 1;

        ControlConcretoCampo::create($data);

        return redirect()->route('control_concreto_campo.index')
                         ->with('success', 'Registro creado correctamente.');
    }

    public function edit($id)
    {
        $control = ControlConcretoCampo::findOrFail($id);
        $proyectos = Proyecto::all();
        return view('control_concreto_campo.form', compact('control', 'proyectos'));
    }

    public function update(Request $request, $id)
    {
        $control = ControlConcretoCampo::findOrFail($id);
        $data = $request->all();
        $data['Actualizado_por'] = Auth::id();
        $data['Fecha_actualizacion'] = now();

        $control->update($data);

        return redirect()->route('control_concreto_campo.index')
                         ->with('success', 'Registro actualizado correctamente.');
    }

    public function destroy($id)
    {
        $control = ControlConcretoCampo::findOrFail($id);
        $control->delete();
        return redirect()->route('control_concreto_campo.index')
                         ->with('success', 'Registro eliminado correctamente.');
    }
}
