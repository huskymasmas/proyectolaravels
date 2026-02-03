<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tramo;
use App\Models\Rodadura;
use App\Models\Cuneta;
use App\Models\TramoElemento;
use App\Models\Eje;
use App\Models\Aldea;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TramoController extends Controller
{
public function index(Request $request)
{
    // ================= FILTRO POR ALDEA =================
    $query = Tramo::leftJoin('tbl_aldea', 'tramo.id_Aldea', '=', 'tbl_aldea.id_aldea')
                  ->select('tramo.*', 'tbl_aldea.Nombre as aldea_nombre');

    if ($request->filled('aldea')) {
        $query->where('tramo.id_Aldea', $request->aldea);
    }

    $tramos = $query->get();
    $aldeas = Aldea::all();

    return view('tramos.index', compact('tramos', 'aldeas'));
}


    public function create()
    {
        $aldeas = Aldea::all();
        $ejes = Eje::all();

        return view('tramos.create', compact('aldeas','ejes'));
    }

    public function store(Request $request)
    {


        $request->validate([
            'id_aldea' => 'required|integer|exists:tbl_aldea,id_aldea',
            'fecha' => 'required|date',
            'tipo_concreto' => 'required|string|max:255',
            'cantidad_concreto_m3' => 'nullable|numeric',
            'temperatura' => 'nullable|numeric',
        ]);

        DB::beginTransaction();

        try {
            $tramo = Tramo::create([
                'id_aldea' => $request->input('id_aldea'),
                'No_envio' => $request->input('No_envio') ?? null,
                'fecha' => $request->input('fecha'),
                'tipo_concreto' => $request->input('tipo_concreto'),
                'cantidad_concreto_m3' => $request->input('cantidad_concreto_m3') ?? 0,
                'supervisor' => $request->input('supervisor'),
                'temperatura' => $request->input('temperatura'),
                'Nombre_aditivo' => $request->input('nombre_aditivo'),
                'Cantidad_lts' => $request->input('cantidad_lts'),
                'Tipo' => $request->input('tipo'),
                'observaciones' => $request->input('observaciones'),
                'Estado' => 1,
            ]);

            // Rodaduras
            if ($request->has('rodaduras') && is_array($request->rodaduras)) {
                foreach ($request->rodaduras as $rod) {
                    if(empty($rod['id_Ejes']) && empty($rod['estacion_inicial']) && empty($rod['estacion_final'])) continue;

                    $rodadura = Rodadura::create([
                        'id_Ejes'=>$rod['id_Ejes'] ?? null,
                        'estacion_inicial'=>$rod['estacion_inicial'] ?? null,
                        'estacion_final'=>$rod['estacion_final'] ?? null,
                        'ancho_prom'=>$rod['ancho_prom'] ?? 0,
                        'm2'=>$rod['m2'] ?? 0,
                        'rendimiento_m3'=>$rod['rendimiento_m3'] ?? 0,
                        'Estado'=>1,
                    ]);

                    TramoElemento::create([
                        'id_tramo'=>$tramo->id_tramo,
                        'id_rodadura'=>$rodadura->id_rodadura,
                        'id_cuneta'=>null,
                        'Estado'=>1,
                    ]);
                }
            }

            // Cunetas
            if ($request->has('cunetas') && is_array($request->cunetas)) {
                foreach ($request->cunetas as $cun) {
                    if(empty($cun['id_Ejes']) && empty($cun['estacion_inicial']) && empty($cun['estacion_final'])) continue;

                    $cuneta = Cuneta::create([
                        'id_Ejes'=>$cun['id_Ejes'] ?? null,
                        'estacion_inicial'=>$cun['estacion_inicial'] ?? null,
                        'estacion_final'=>$cun['estacion_final'] ?? null,
                        'ancho_prom'=>$cun['ancho_prom'] ?? 0,
                        'm2'=>$cun['m2'] ?? 0,
                        'rendimiento_m3'=>$cun['rendimiento_m3'] ?? 0,
                        'Estado'=>1,
                    ]);

                    TramoElemento::create([
                        'id_tramo'=>$tramo->id_tramo,
                        'id_rodadura'=>null,
                        'id_cuneta'=>$cuneta->id_cuneta,
                        'Estado'=>1,
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('tramos.index')->with('success','Tramo guardado correctamente.');
        }
        catch(\Exception $e) {
            DB::rollBack();
            dd('❌ Error: '.$e->getMessage());
        }
    }
}
