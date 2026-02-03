<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TramoAplicacion;
use App\Models\RodaduraAplicacion;
use App\Models\CunetaAplicacion;
use App\Models\TramoElementoAplicacion;
use App\Models\Aldea;
use App\Models\Eje;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class TramoAplicacionController extends Controller
{
public function index(Request $request)
{
    // ================= FILTRO POR ALDEA =================
    $query = TramoAplicacion::leftJoin('tbl_aldea', 'tramo_aplicacion.id_aldea', '=', 'tbl_aldea.id_aldea')
                             ->select('tramo_aplicacion.*', 'tbl_aldea.Nombre as aldea_nombre');

    if ($request->filled('aldea')) {
        $query->where('tramo_aplicacion.id_aldea', $request->aldea);
    }

    $tramos = $query->orderBy('tramo_aplicacion.id_tramo', 'desc')->get();
    $aldeas = Aldea::all();

    return view('tramo_aplicacion.index', compact('tramos', 'aldeas'));
}


    public function create()
    {
        $aldeas = Aldea::all();
        $ejes = Eje::all();
        return view('tramo_aplicacion.form', compact('aldeas', 'ejes'));
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $tramo = TramoAplicacion::create([
                'id_aldea' => $request->id_aldea,
                'fecha' => $request->fecha,
                'aplicador' => $request->aplicador ?? null,
                'cubeta_bomba' => $request->cubeta_bomba ?? null,
                'supervisor' => $request->supervisor ?? null,
                'observaciones' => $request->observaciones ?? null,
                'Aditivo_Ancho' => $request->Aditivo_Ancho ?? null,
                'Rendimiento_M2' => $request->Rendimiento_M2 ?? null,
                'Estado' => 1,
            ]);

            // Guardar rodaduras
            if ($request->filled('rodaduras')) {
                foreach ($request->rodaduras as $rod) {
                    if(empty($rod['id_Ejes']) && empty($rod['estacion_inicial']) && empty($rod['estacion_final'])) continue;

                    $rodadura = RodaduraAplicacion::create([
                        'id_Ejes' => $rod['id_Ejes'] ?? null,
                        'estacion_inicial' => $rod['estacion_inicial'] ?? null,
                        'estacion_final' => $rod['estacion_final'] ?? null,
                        'Ancho' => $rod['ancho'] ?? null,
                        'Rendimiento_M2' => $rod['rendimiento_m2'] ?? 0,
                        'Estado' => 1,
                    ]);

                    TramoElementoAplicacion::create([
                        'id_tramo' => $tramo->id_tramo,
                        'id_rodadura' => $rodadura->id_rodadura,
                        'id_cuneta' => null,
                        'Estado' => 1,
                    ]);
                }
            }

            // Guardar cunetas
            if ($request->filled('cunetas')) {
                foreach ($request->cunetas as $cun) {
                    if(empty($cun['id_Ejes']) && empty($cun['estacion_inicial']) && empty($cun['estacion_final'])) continue;

                    $cuneta = CunetaAplicacion::create([
                        'id_Ejes' => $cun['id_Ejes'] ?? null,
                        'estacion_inicial' => $cun['estacion_inicial'] ?? null,
                        'estacion_final' => $cun['estacion_final'] ?? null,
                        'Ancho' => $cun['ancho'] ?? null,
                        'Rendimiento_M2' => $cun['rendimiento_m2'] ?? 0,
                        'Estado' => 1,
                    ]);

                    TramoElementoAplicacion::create([
                        'id_tramo' => $tramo->id_tramo,
                        'id_rodadura' => null,
                        'id_cuneta' => $cuneta->id_cuneta,
                        'Estado' => 1,
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('tramo_aplicacion.index')->with('success', '✅ Tramo guardado correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            dd('❌ Error: '.$e->getMessage());
        }
    }

    public function edit($id)
    {
        $tramo = TramoAplicacion::with(['rodaduras', 'cunetas'])->findOrFail($id);
        $aldeas = Aldea::all();
        $ejes = Eje::all();
        return view('tramo_aplicacion.form', compact('tramo', 'aldeas', 'ejes'));
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $tramo = TramoAplicacion::findOrFail($id);
            $tramo->update([
                'id_aldea' => $request->id_aldea,
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

            // Eliminar elementos antiguos
            TramoElementoAplicacion::where('id_tramo', $tramo->id_tramo)->delete();
            RodaduraAplicacion::whereIn('id_rodadura', $tramo->rodaduras->pluck('id_rodadura'))->delete();
            CunetaAplicacion::whereIn('id_cuneta', $tramo->cunetas->pluck('id_cuneta'))->delete();

            // Guardar nuevas rodaduras
            if ($request->filled('rodaduras')) {
                foreach ($request->rodaduras as $rod) {
                    if(empty($rod['id_Ejes']) && empty($rod['estacion_inicial']) && empty($rod['estacion_final'])) continue;

                    $rodadura = RodaduraAplicacion::create([
                        'id_Ejes' => $rod['id_Ejes'] ?? null,
                        'estacion_inicial' => $rod['estacion_inicial'] ?? null,
                        'estacion_final' => $rod['estacion_final'] ?? null,
                        'Ancho' => $rod['ancho'] ?? null,
                        'Rendimiento_M2' => $rod['rendimiento_m2'] ?? 0,
                        'Estado' => 1,
                    ]);

                    TramoElementoAplicacion::create([
                        'id_tramo' => $tramo->id_tramo,
                        'id_rodadura' => $rodadura->id_rodadura,
                        'id_cuneta' => null,
                        'Estado' => 1,
                    ]);
                }
            }

            // Guardar nuevas cunetas
            if ($request->filled('cunetas')) {
                foreach ($request->cunetas as $cun) {
                    if(empty($cun['id_Ejes']) && empty($cun['estacion_inicial']) && empty($cun['estacion_final'])) continue;

                    $cuneta = CunetaAplicacion::create([
                        'id_Ejes' => $cun['id_Ejes'] ?? null,
                        'estacion_inicial' => $cun['estacion_inicial'] ?? null,
                        'estacion_final' => $cun['estacion_final'] ?? null,
                        'Ancho' => $cun['ancho'] ?? null,
                        'Rendimiento_M2' => $cun['rendimiento_m2'] ?? 0,
                        'Estado' => 1,
                    ]);

                    TramoElementoAplicacion::create([
                        'id_tramo' => $tramo->id_tramo,
                        'id_rodadura' => null,
                        'id_cuneta' => $cuneta->id_cuneta,
                        'Estado' => 1,
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('tramo_aplicacion.index')->with('success', '✅ Tramo actualizado correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            dd('❌ Error: '.$e->getMessage());
        }
    }
}
