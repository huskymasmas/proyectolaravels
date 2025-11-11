<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TramoAplicacion;
use App\Models\RodaduraAplicacion;
use App\Models\CunetaAplicacion;
use App\Models\TramoElementoAplicacion;
use App\Models\Proyecto;
use App\Models\Eje;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TramoAplicacionController extends Controller
{
    public function index(Request $request)
    {
        $query = TramoAplicacion::with(['rodaduras', 'cunetas', 'proyecto']);

        if ($request->filled('proyecto')) {
            $query->where('id_Proyecto', $request->proyecto);
        }

        $tramos = $query->orderBy('id_tramo', 'desc')->get();
        $proyectos = Proyecto::all();

        return view('tramo_aplicacion.index', compact('tramos', 'proyectos'));
    }

    public function create()
    {
        $proyectos = Proyecto::all();
        $ejes = Eje::all();

        return view('tramo_aplicacion.form', compact('proyectos', 'ejes'));
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $tramo = TramoAplicacion::create([
                'id_Proyecto' => $request->id_Proyecto,
                'fecha' => $request->fecha,
                'aplicador' => $request->aplicador ?? null,
                'cubeta_bomba' => $request->cubeta_bomba ?? null,
                'supervisor' => $request->supervisor ?? null,
                'observaciones' => $request->observaciones ?? null,
                'Aditivo_Ancho' => $request->Aditivo_Ancho ?? null,
                'Rendimiento_M2' => $request->Rendimiento_M2 ?? null,
                'Estado' => 1,
                'Creado_por' => Auth::id(),
                'Fecha_creacion' => now(),
            ]);

            // Rodaduras
            if ($request->filled('rodaduras')) {
                foreach ($request->rodaduras as $rod) {
                    $rodadura = RodaduraAplicacion::create([
                        'id_Ejes' => $rod['id_Ejes'] ?? null,
                        'estacion_inicial' => $rod['estacion_inicial'] ?? null,
                        'estacion_final' => $rod['estacion_final'] ?? null,
                        'Estado' => 1,
                        'Creado_por' => Auth::id(),
                        'Fecha_creacion' => now(),
                    ]);

                    TramoElementoAplicacion::create([
                        'id_tramo' => $tramo->id_tramo,
                        'id_rodadura' => $rodadura->id_rodadura,
                        'id_cuneta' => null
                    ]);
                }
            }

            // Cunetas
            if ($request->filled('cunetas')) {
                foreach ($request->cunetas as $cun) {
                    $cuneta = CunetaAplicacion::create([
                        'id_Ejes' => $cun['id_Ejes'] ?? null,
                        'estacion_inicial' => $cun['estacion_inicial'] ?? null,
                        'estacion_final' => $cun['estacion_final'] ?? null,
                        'Estado' => 1,
                        'Creado_por' => Auth::id(),
                        'Fecha_creacion' => now(),
                    ]);

                    TramoElementoAplicacion::create([
                        'id_tramo' => $tramo->id_tramo,
                        'id_rodadura' => null,
                        'id_cuneta' => $cuneta->id_cuneta
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('tramo_aplicacion.index')
                             ->with('success', '✅ Tramo, rodaduras y cunetas guardadas correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e->getMessage());
        }
    }
    public function edit($id)
{
    $tramo = TramoAplicacion::with(['rodaduras', 'cunetas'])->findOrFail($id);
    $proyectos = Proyecto::all();
    $ejes = Eje::all();

    return view('tramo_aplicacion.form', compact('tramo', 'proyectos', 'ejes'));
}
public function update(Request $request, $id)
{
    DB::beginTransaction();
    try {
        $tramo = TramoAplicacion::findOrFail($id);

        // Actualizar datos generales del tramo
        $tramo->update([
            'id_Proyecto' => $request->id_Proyecto,
            'fecha' => $request->fecha,
            'aplicador' => $request->aplicador ?? null,
            'cubeta_bomba' => $request->cubeta_bomba ?? null,
            'supervisor' => $request->supervisor ?? null,
            'observaciones' => $request->observaciones ?? null,
            'Aditivo_Ancho' => $request->Aditivo_Ancho ?? null,
            'Rendimiento_M2' => $request->Rendimiento_M2 ?? null,
            'Actualizado_por' => Auth::id(),
            'Fecha_actualizacion' => now(),
        ]);

        // Eliminar rodaduras y cunetas existentes relacionadas
        TramoElementoAplicacion::where('id_tramo', $tramo->id_tramo)->delete();
        RodaduraAplicacion::whereIn('id_rodadura', $tramo->rodaduras->pluck('id_rodadura'))->delete();
        CunetaAplicacion::whereIn('id_cuneta', $tramo->cunetas->pluck('id_cuneta'))->delete();

        // Guardar rodaduras nuevas
        if ($request->filled('rodaduras')) {
            foreach ($request->rodaduras as $rod) {
                $rodadura = RodaduraAplicacion::create([
                    'id_Ejes' => $rod['id_Ejes'] ?? null,
                    'estacion_inicial' => $rod['estacion_inicial'] ?? null,
                    'estacion_final' => $rod['estacion_final'] ?? null,
                    'ancho' => $rod['ancho'] ?? null,
                    'rendimiento_m2' => $rod['rendimiento_m2'] ?? null,
                    'Estado' => 1,
                    'Creado_por' => Auth::id(),
                    'Fecha_creacion' => now(),
                ]);

                TramoElementoAplicacion::create([
                    'id_tramo' => $tramo->id_tramo,
                    'id_rodadura' => $rodadura->id_rodadura,
                    'id_cuneta' => null
                ]);
            }
        }

        // Guardar cunetas nuevas
        if ($request->filled('cunetas')) {
            foreach ($request->cunetas as $cun) {
                $cuneta = CunetaAplicacion::create([
                    'id_Ejes' => $cun['id_Ejes'] ?? null,
                    'estacion_inicial' => $cun['estacion_inicial'] ?? null,
                    'estacion_final' => $cun['estacion_final'] ?? null,
                    'ancho' => $cun['ancho'] ?? null,
                    'rendimiento_m2' => $cun['rendimiento_m2'] ?? null,
                    'Estado' => 1,
                    'Creado_por' => Auth::id(),
                    'Fecha_creacion' => now(),
                ]);

                TramoElementoAplicacion::create([
                    'id_tramo' => $tramo->id_tramo,
                    'id_rodadura' => null,
                    'id_cuneta' => $cuneta->id_cuneta
                ]);
            }
        }

        DB::commit();
        return redirect()->route('tramo_aplicacion.index')
                         ->with('success', '✅ Tramo actualizado correctamente.');
    } catch (\Exception $e) {
        DB::rollBack();
        dd($e->getMessage());
    }
}


}
