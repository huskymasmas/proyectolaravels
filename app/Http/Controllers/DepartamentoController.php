<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Departamento;

use Illuminate\Support\Facades\Auth;

class DepartamentoController extends Controller
{
    public function index()
    {
        $departamentos = Departamento::all();
        return view('departamentos.index', compact('departamentos'));
    }

    public function create()
    {
       
        return view('departamentos.form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'Nombres' => 'required|string|max:255',
        ]);

        Departamento::create([
            'Nombres' => $request->Nombres,
            'Creado_por' => Auth::id(),
            'Actualizado_por' => Auth::id(),
            'Fecha_creacion' => now(),
            'Fecha_actualizacion' => now(),
        ]);

        return redirect()->route('departamentos.index')->with('success', 'Departamento creado correctamente.');
    }

    public function edit($id)
    {
        $departamento = Departamento::findOrFail($id);
        return view('departamentos.form', compact('departamento'));
    }

    public function update(Request $request, $id)
    {
        $departamento = Departamento::findOrFail($id);

        $request->validate([
            'Nombres' => 'required|string|max:255',
        ]);

        $departamento->update([
            'Nombres' => $request->Nombres,
            'Actualizado_por' => Auth::id(),
            'Fecha_actualizacion' => now(),
        ]);

        return redirect()->route('departamentos.index')->with('success', 'Departamento actualizado correctamente.');
    }

    public function destroy($id)
    {
        Departamento::findOrFail($id)->delete();
        return redirect()->route('departamentos.index')->with('success', 'Departamento eliminado correctamente.');
    }
}
