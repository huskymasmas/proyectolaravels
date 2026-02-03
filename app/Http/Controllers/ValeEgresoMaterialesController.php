<?php

namespace App\Http\Controllers;

use App\Models\ValeEgresoMaterialesVarios;
use App\Models\BodegaParaProyectos;
use App\Models\EstacionBodega;
use App\Models\Proyecto;
use App\Models\Unidad;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ValeEgresoMaterialesController extends Controller
{
    // INDEX - Listado de egresos con filtro por proyecto
    public function index(Request $request)
    {
        $proyectos = Proyecto::all();
        $query = ValeEgresoMaterialesVarios::with(['unidad', 'proyecto']);

        if ($request->filled('id_Proyecto')) {
            $query->where('id_Proyecto', $request->id_Proyecto);
        }

        $vales = $query->orderBy('Fecha', 'desc')->get();

        return view('vale_egreso_material.index', compact('vales', 'proyectos'));
    }

    // CREATE - Formulario de egreso
    public function create()
    {
        $proyectos = Proyecto::all();

        // Solo materiales con unidad de medida
        $valesIngreso = BodegaParaProyectos::whereNotNull('id_Unidades')->get();

        return view('vale_egreso_material.create', compact('proyectos', 'valesIngreso'));
    }

    // STORE - Guardar egreso
    public function store(Request $request)
    {
        $data = $request->all();

        // Validar proyecto
        if (empty($data['id_Proyecto'])) {
            return redirect()->back()->withErrors('Debe seleccionar un proyecto para el egreso.')->withInput();
        }

        // Validar material en la bodega del proyecto
        $bodega = BodegaParaProyectos::where('id_Proyecto', $data['id_Proyecto'])
                    ->where('Material', $data['Nombre'])
                    ->first();

        if (!$bodega) {
            return redirect()->back()->withErrors('El material seleccionado no existe en la bodega del proyecto.')->withInput();
        }

        // Validar unidad
        if (empty($bodega->id_Unidades)) {
            return redirect()->back()->withErrors('No se puede egresar este material porque no tiene unidad de medida asignada.')->withInput();
        }
        // Validar unidad
        if ($bodega->Almazenado <= 0) {
            return redirect()->back()->withErrors('No se puede egresar material porque no hay suficiente almacenado')->withInput();
        }
        
        // Asignar unidad automáticamente
        $data['id_Unidades'] = $bodega->id_Unidades;

        // Registrar el egreso
        $vale = ValeEgresoMaterialesVarios::create($data);

        // Actualizar bodega del proyecto
        $bodega->Almazenado -= $data['cantidad'];
        $bodega->Usado += $data['cantidad'];
        $bodega->save();

        $nombreProyecto = DB::table('tbl_proyecto')
                    ->where('id_Proyecto', $data['id_Proyecto'])
                    ->value('Nombre');

        // Registrar movimiento en EstacionBodega
        EstacionBodega::create([
            'material' => $data['Nombre'],
            'cantidad' => $data['cantidad'],
            'id_Unidades' => $data['id_Unidades'],
            'proyecto' => 'fue egresado de proyecto'.$nombreProyecto ?? ' egresado Bodega general',
            'Estado' => 1
        ]);

        return redirect()->route('vale_egreso_material.index')
                         ->with('success', 'Egreso registrado correctamente');
    }
}
