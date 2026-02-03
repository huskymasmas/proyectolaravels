<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ControlProduccion;
use App\Models\Proyecto;
use App\Models\Tipo_dosificador;
use App\Models\ValeDespacho;
use App\Models\Configuracion;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ControlProduccionController extends Controller
{
    public function index()
    {
        $proyectos = Proyecto::all();
        $dosificaciones = Tipo_dosificador::all();

        $controles = ControlProduccion::with(['proyecto', 'dosificacion'])
            ->orderBy('id_control_produccion', 'DESC')
            ->get();

        return view('control_produccion.index', compact(
            'proyectos',
            'dosificaciones',
            'controles'
        ));
    }

    /**
     * OBTENER SIGUIENTE VALE DISPONIBLE (por Proyecto)
     */
    public function siguienteVale(Request $request)
    {
        $request->validate([
            'id_Proyecto' => 'required|numeric'
        ]);

        // Buscar Vale → Despacho → Proyecto
        $vale = ValeDespacho::whereHas('despacho', function ($q) use ($request) {
                $q->where('id_Proyecto', $request->id_Proyecto);
            })
            ->where('usado', 0)
            ->orderBy('id_Vale_despacho', 'DESC')
            ->with(['despacho', 'dosificacion', 'aditivo'])
            ->first();

        if (!$vale) {
            return response()->json([
                'ok' => false,
                'message' => 'No hay vales disponibles para este proyecto.'
            ]);
        }

     

        // Datos calculados
        $data = [
            'id' => $vale->id_Vale_despacho,
            'placa' => $vale->despacho->Placa_numero ?? null,
            'conductor' => $vale->aditivo->Nombre_coductor 
                            ?? $vale->despacho->Conductor 
                            ?? null,
            'sacos_cemento' => $vale->dosificacion->Sacos_Cemento ?? 0,
            'cemento_granel_kg' => $vale->dosificacion->kg_cemento_granel ?? 0,
            'arena_kg' => $vale->dosificacion->Kg_arena ?? 0,
            'piedrin_kg' => $vale->dosificacion->kg_piedirn ?? 0,
            'aditivo_total' => (
                ($vale->aditivo->Cantidad1 ?? 0) +
                ($vale->aditivo->Cantidad2 ?? 0) +
                ($vale->aditivo->Cantidad3 ?? 0) +
                ($vale->aditivo->Cantidad4 ?? 0)
            )
        ];

        return response()->json(['ok' => true, 'vale' => $data]);
    }


    /**
     * STORE – Crear Control de Producción usando VALE DISPONIBLE
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_Proyecto' => 'required|numeric',
            'id_Tipo_dosificacion' => 'required|numeric',
        ]);

        DB::beginTransaction();

        try {

            /** 1️⃣ Buscar Vale disponible por Proyecto **/
            $vale = ValeDespacho::whereHas('despacho', function ($q) use ($request) {
                    $q->where('id_Proyecto', $request->id_Proyecto);
                })
                ->where('usado', 0)
                ->lockForUpdate()
                ->orderBy('id_Vale_despacho', 'DESC')
                ->with(['despacho', 'dosificacion', 'aditivo'])
                ->first();

            if (!$vale) {
                return back()->with('error', 'No hay vales disponibles para este proyecto.');
            }

            // Alias para campos
            $dos = $vale->dosificacion;
            $des = $vale->despacho;
            $adi = $vale->aditivo;

            /** 2️⃣ Calcular cantidades **/
            $cemento_sacos = $dos->Sacos_Cemento ?? 0;
            $cemento_total = ($cemento_sacos * 42.5) + ($dos->kg_cemento_granel ?? 0);

            $arena_kg = $dos->Kg_arena ?? 0;
            $piedrin_kg = $dos->kg_piedirn ?? 0;

            $aditivo = (
                ($adi->Cantidad1 ?? 0) +
                ($adi->Cantidad2 ?? 0) +
                ($adi->Cantidad3 ?? 0) +
                ($adi->Cantidad4 ?? 0)
            );
                $Controller_arena = Configuracion::where('Parametros', 'Like','%arena%')
                                        ->where('id_Proyecto', $request->id_Proyecto)
                                        ->value('Valor');
                $Controller_piedrin = Configuracion::where('Parametros','Like','%Piedrin%')
                              ->where('id_Proyecto', $request->id_Proyecto)
                              ->value('Valor');


            /** 3️⃣ Crear Registro Control Producción **/
            $control = ControlProduccion::create([
                'id_Proyecto' => $request->id_Proyecto,
                'id_Tipo_dosificacion' => $request->id_Tipo_dosificacion,

                'fecha' => now()->format('Y-m-d'),

                'cemento_sacos' => $cemento_sacos,
                'Cemento_total' => $cemento_total,
                'Arena_kg' => $arena_kg,
                'Piedrin_kg' => $piedrin_kg,
                'Aditivo' => $aditivo,

                'Piedrin_salida' => $piedrin_kg / $Controller_piedrin ?? 0,
                'Arena_salida' => $arena_kg / $Controller_arena ?? 0,

                'Coductor' => substr(($adi->Nombre_coductor ?? $des->Conductor ?? ''), 0, 50),
                'Placa' => substr(($des->Placa_numero ?? ''), 0, 50),

                'viajes' => 1,

                // Relación con vale
                'id_vale' => $vale->id_Vale_despacho,

                // Auditoría obligatoria
                'Creado_por' => Auth::id(),
                'Fecha_creacion' => now(),
            ]);

            /** 4️⃣ Marcar el Vale como USADO **/
            $vale->update([
                'usado' => 1,
                'estado' => 0,
            ]);

            DB::commit();

            return back()->with(
                'success',
                "Control de Producción creado con el Vale No. {$vale->id_Vale_despacho}."
            );

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', "Error: " . $e->getMessage());
        }
    }
}
