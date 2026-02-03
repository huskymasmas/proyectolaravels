<?php

namespace App\Http\Controllers;

use App\Models\ControlConcretoCampo;
use App\Models\Aldea;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ControlConcretoCampoController extends Controller
{
    public function index()
    {
        $registros = ControlConcretoCampo::with('aldea')->get();
        return view('control_concreto_campo.index', compact('registros'));
    }

    public function create()
    {
        $aldeas = Aldea::all();
        return view('control_concreto_campo.form', [
            'registro' => new ControlConcretoCampo(),
            'aldeas' => $aldeas
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_Aldea' => 'required|integer',
            'fecha' => 'required|date',
            'codigo_envio_camion' => 'required|string|max:50',
            'responsable' => 'required|string|max:100',
        ]);

        $data = $request->all();
        $data['Estado'] = 1; // 👈 Estado por defecto
        $data['Creado_por'] = Auth::id() ?? 1;
        $data['Fecha_creacion'] = now();

        ControlConcretoCampo::create($data);

        return redirect()->route('control_concreto_campo.index')
            ->with('success', 'Registro creado correctamente.');
    }

    public function edit($id)
    {
        $registro = ControlConcretoCampo::findOrFail($id);
        $aldeas = Aldea::all();
        return view('control_concreto_campo.form', compact('registro', 'aldeas'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'id_Aldea' => 'required|integer',
            'fecha' => 'required|date',
            'codigo_envio_camion' => 'required|string|max:50',
            'responsable' => 'required|string|max:100',
        ]);

        $registro = ControlConcretoCampo::findOrFail($id);

        $data = $request->except(['Estado']); // 👈 Evita cambiar Estado
        $data['Actualizado_por'] = Auth::id() ?? 1;
        $data['Fecha_actualizacion'] = now();

        $registro->update($data);

        return redirect()->route('control_concreto_campo.index')
            ->with('success', 'Registro actualizado correctamente.');
    }

    public function destroy($id)
    {
        $registro = ControlConcretoCampo::findOrFail($id);
        $registro->delete();

        return redirect()->route('control_concreto_campo.index')
            ->with('success', 'Registro eliminado correctamente.');
    }
}
