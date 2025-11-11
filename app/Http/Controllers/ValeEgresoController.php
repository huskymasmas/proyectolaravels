<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ValeDespacho;
use App\Models\DespachoConcreto;
use App\Models\AditivoAplicados;
use App\Models\DosificacionVale;
use App\Models\BodegaGeneral;
use App\Models\FormatoControlDespachoPlanta;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ValeEgresoController extends Controller
{
    // 1️⃣ Mostrar lista de vales
    public function index()
    {
        $vales = ValeDespacho::with(['despacho.proyecto', 'despacho.empresa', 'dosificacion', 'aditivo'])->get();
        return view('vale_egreso.index', compact('vales'));
    }

    // 2️⃣ Mostrar formulario
    public function create()
    {
        $proyectos = \App\Models\Proyecto::all();
        $empresas = \App\Models\Empresa::all();
        return view('vale_egreso.create', compact('proyectos', 'empresas'));
    }

    // 3️⃣ Guardar vale
    public function store(Request $request)
    {
        $request->validate([
            'No_vale' => 'required|numeric',
            'Codigo_planta' => 'required',
            'id_Proyecto' => 'required|numeric',
            'Volumen_carga_M3' => 'required|numeric',
            'Tipo_Concreto' => 'required',
        ]);

        DB::beginTransaction();

        try {
            $usuarioId = Auth::id();

            // 1️⃣ Crear despacho
            $despacho = DespachoConcreto::create(array_merge(
                $request->only([
                    'Codigo_planta','id_Proyecto','Fecha','Volumen_carga_M3',
                    'Hora_salida_plata','Tipo_Concreto','id_Empresa','Inicio_Carga',
                    'Finaliza_carga','Hora_llega_Proyecto','Tipo_elemento',
                    'Placa_numero','Estado'
                ]),
                [
                    'Creado_por' => $usuarioId,
                    'Fecha_creacion' => now()
                ]
            ));

            // 2️⃣ Crear dosificación
            $dosificacion = DosificacionVale::create(array_merge(
                $request->only([
                    'kg_cemento_granel','Sacos_Cemento','kg_piedirn','Kg_arena','lts_agua','Estado'
                ]),
                [
                    'Creado_por' => $usuarioId,
                    'Fecha_creacion' => now()
                ]
            ));

            // 3️⃣ Crear aditivos
            $aditivoData = $request->only([
                'Nombre1','Nombre2','Nombre3','Nombre4',
                'Cantidad1','Cantidad2','Cantidad3','Cantidad4',
                'Firma1_ruta_imagen_encargado_palata','Firma2_ruta_imagen_coductor','Firma3_ruta_imagen_Resibi_conforme',
                'Nombre_encargado_palata','Nombre_coductor','Nombre_Resibi_conforme','Estado'
            ]);
            $aditivoData['Creado_por'] = $usuarioId;
            $aditivoData['Fecha_creacion'] = now();
            $aditivo = AditivoAplicados::create($aditivoData);

            // 4️⃣ Crear vale de despacho
            $vale = ValeDespacho::create([
                'No_vale' => $request->No_vale,
                'id_Despacho_concreto' => $despacho->id_Despacho_concreto,
                'id_Dosificacion_vale' => $dosificacion->id_Dosificacion_vale,
                'id_Aditivo_aplicados' => $aditivo->id_Aditivo_aplicados,
                'Estado' => 1,
                'Creado_por' => $usuarioId,
                'Fecha_creacion' => now(),
            ]);

            // 5️⃣ Crear registro en Formato de Control de Despacho Planta
            FormatoControlDespachoPlanta::create([
                'No_envio' => $vale->No_vale,
                'id_Proyecto' => $despacho->id_Proyecto,
                'Tipo_de_Concreto_ps' => $despacho->Tipo_Concreto,
                'Cantidad_Concreto_mT3' => $despacho->Volumen_carga_M3,
                'Concreto_granel_kg' => $dosificacion->kg_cemento_granel,
                'Concreto_sacos_kg' => $dosificacion->Sacos_Cemento,
                'total' => $dosificacion->kg_cemento_granel + ($dosificacion->Sacos_Cemento * 42.5),
                'kg_Piedrin' => $dosificacion->kg_piedirn,
                'kg_Arena' => $dosificacion->Kg_arena,
                'Lts_Agua' => $dosificacion->lts_agua,
                'Aditivo1' => $aditivo->Nombre1,
                'Aditivo2' => $aditivo->Nombre2,
                'Aditivo3' => $aditivo->Nombre3,
                'Aditivo4' => $aditivo->Nombre4,
                'cantidad1' => $aditivo->Cantidad1,
                'cantidad2' => $aditivo->Cantidad2,
                'cantidad3' => $aditivo->Cantidad3,
                'cantidad4' => $aditivo->Cantidad4,
                'id_Empleados' => $usuarioId, // supervisor = usuario actual
                'Observaciones' => '', // vacío por defecto
                'Estado' => 1,
                'Creado_por' => $usuarioId,
                'Fecha_creacion' => now(),
            ]);

            // 6️⃣ Actualizar bodega
            $this->actualizarBodega('cemento', $dosificacion->kg_cemento_granel + ($dosificacion->Sacos_Cemento * 42.5));
            $this->actualizarBodega('piedrin', $dosificacion->kg_piedirn);
            $this->actualizarBodega('arena', $dosificacion->Kg_arena);
            $this->actualizarBodega('agua', $dosificacion->lts_agua);

            // 7️⃣ Aditivos en bodega
            for ($i = 1; $i <= 4; $i++) {
                $nombreCampo = 'Nombre' . $i;
                $cantidadCampo = 'Cantidad' . $i;
                if (!empty($aditivo->$nombreCampo) && !empty($aditivo->$cantidadCampo)) {
                    $this->actualizarBodegaLike($aditivo->$nombreCampo, $aditivo->$cantidadCampo);
                }
            }

            DB::commit();
            return redirect()->route('vale_egreso.index')->with('success', 'Vale egreso creado correctamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage())->withInput();
        }
    }

    // 🔧 Descontar materiales
    private function actualizarBodega($nombre, $cantidad)
    {
        if (!$cantidad || $cantidad <= 0) return;

        $producto = BodegaGeneral::where('Nombre', 'LIKE', "%{$nombre}%")->first();
        if (!$producto) throw new \Exception("No existe el producto '{$nombre}' en bodega.");
        if ($producto->Cantidad < $cantidad) throw new \Exception("Stock insuficiente para '{$nombre}'.");

        $producto->Cantidad -= $cantidad;
        $producto->save();
    }

    // 🔧 Descontar aditivos
    private function actualizarBodegaLike($nombre, $cantidad)
    {
        if (!$cantidad || $cantidad <= 0) return;

        $producto = BodegaGeneral::where('Nombre', 'LIKE', "%{$nombre}%")->first();
        if (!$producto) throw new \Exception("El aditivo '{$nombre}' no existe en bodega.");
        if ($producto->Cantidad < $cantidad) throw new \Exception("Stock insuficiente para '{$nombre}'.");

        $producto->Cantidad -= $cantidad;
        $producto->save();
    }
}
