<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tramo;
use App\Models\Rodadura;
use App\Models\Cuneta;
use App\Models\TramoElemento;
use App\Models\Eje;
use App\Models\Proyecto;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TramoController extends Controller
{
    // 1️⃣ Index con filtro
    public function index(Request $request)
    {
        $query = Tramo::with(['rodaduras', 'cunetas', 'proyecto']);

        if ($request->filled('proyecto')) {
            $query->where('id_Proyecto', $request->proyecto);
        }
        $tramos = $query->get();
        $proyectos = Proyecto::all();

        return view('tramos.index', compact('tramos', 'proyectos'));
    }

    // 2️⃣ Formulario
    public function create()
    {
        $proyectos = Proyecto::all();
        $ejes = Eje::all();

        return view('tramos.create', compact('proyectos', 'ejes'));
    }

public function store(Request $request)
{
    DB::beginTransaction();

    try {
        // 1️⃣ Crear tramo
        $tramo = Tramo::create([
            'id_Proyecto' => $request->id_Proyecto,
            'No_envio' => $request->no_envio,
            'fecha' => $request->fecha,
            'tipo_concreto' => $request->tipo_concreto,
            'cantidad_concreto_m3' => $request->cantidad_concreto_m3,
            'supervisor' => $request->supervisor ?? null,
            'temperatura' => $request->temperatura ?? null,
            'Nombre_aditivo' => $request->nombre_aditivo ?? null,
            'Cantidad_lts' => $request->cantidad_lts ?? null,
            'Tipo' => $request->tipo ?? null,
            'observaciones' => $request->observaciones ?? null,
            'Estado' => 1,
            'Creado_por' => Auth::id(),
            'Fecha_creacion' => now(),
        ]);

        // 2️⃣ Guardar rodaduras completas
        if ($request->filled('rodaduras')) {
            foreach ($request->rodaduras as $rod) {
                $rodadura = Rodadura::create([
                    'id_Ejes' => $rod['id_Ejes'] ?? null,
                    'estacion_inicial' => $rod['estacion_inicial'] ?? null,
                    'estacion_final' => $rod['estacion_final'] ?? null,
                    'ancho_prom' => $rod['ancho_prom'] ?? null,
                    'm2' => $rod['m2'] ?? null,
                    'rendimiento_m3' => $rod['rendimiento_m3'] ?? null,
                    'Estado' => 1,
                    'Creado_por' => Auth::id(),
                    'Fecha_creacion' => now(),
                ]);

                // Relación con tramo
                TramoElemento::create([
                    'id_tramo' => $tramo->id_tramo,
                    'id_rodadura' => $rodadura->id_rodadura,
                    'id_cuneta' => null,
                    'Estado' => 1,
                    'Creado_por' => Auth::id(),
                    'Fecha_creacion' => now(),
                ]);
            }
        }

        // 3️⃣ Guardar cunetas completas
        if ($request->filled('cunetas')) {
            foreach ($request->cunetas as $cun) {
                $cuneta = Cuneta::create([
                    'id_Ejes' => $cun['id_Ejes'] ?? null,
                    'estacion_inicial' => $cun['estacion_inicial'] ?? null,
                    'estacion_final' => $cun['estacion_final'] ?? null,
                    'ancho_prom' => $cun['ancho_prom'] ?? null,
                    'm2' => $cun['m2'] ?? null,
                    'rendimiento_m3' => $cun['rendimiento_m3'] ?? null,
                    'Estado' => 1,
                    'Creado_por' => Auth::id(),
                    'Fecha_creacion' => now(),
                ]);

                TramoElemento::create([
                    'id_tramo' => $tramo->id_tramo,
                    'id_rodadura' => null,
                    'id_cuneta' => $cuneta->id_cuneta,
                    'Estado' => 1,
                    'Creado_por' => Auth::id(),
                    'Fecha_creacion' => now(),
                ]);
            }
        }

        DB::commit();
        return redirect()->route('tramos.index')
                         ->with('success', '✅ Tramo, rodaduras y cunetas guardadas correctamente.');
    } catch (\Exception $e) {
        DB::rollBack();
        dd($e->getMessage());
    }
}



}
