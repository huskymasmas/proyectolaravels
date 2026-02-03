<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ValeDespacho;
use App\Models\DespachoConcreto;
use App\Models\AditivoAplicados;
use App\Models\DosificacionVale;
use App\Models\BodegaGeneral;
use App\Models\BodegaParaProyectos;
use App\Models\FormatoControlDespachoPlanta;
use App\Models\EstacionBodega;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ValeEgresoController extends Controller
{
    public function index()
    {
        $vales = ValeDespacho::with(['despacho.proyecto', 'despacho.empresa', 'dosificacion', 'aditivo'])->get();
        return view('vale_egreso.index', compact('vales'));
    }

    public function create()
    {
        $proyectos = \App\Models\Proyecto::all();
        $empresas = \App\Models\Empresa::all();
        return view('vale_egreso.create', compact('proyectos', 'empresas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'Codigo_planta' => 'required',
            'id_Proyecto' => 'required|numeric',
            'Volumen_carga_M3' => 'required|numeric',
            'Tipo_Concreto' => 'required',
        ]);

        DB::beginTransaction();

        try {

            // ⬅️ ACTIVAR LOG DE QUERIES
            DB::enableQueryLog();

            $usuarioId = Auth::id();

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

            $dosificacion = DosificacionVale::create(array_merge(
                $request->only([
                    'kg_cemento_granel','Sacos_Cemento','kg_piedirn','Kg_arena','lts_agua','Estado'
                ]),
                [
                    'Creado_por' => $usuarioId,
                    'Fecha_creacion' => now()
                ]
            ));

            $aditivoData = $request->only([
                'Nombre1','Nombre2','Nombre3','Nombre4',
                'Cantidad1','Cantidad2','Cantidad3','Cantidad4',
                'Firma1_ruta_imagen_encargado_palata','Firma2_ruta_imagen_coductor','Firma3_ruta_imagen_Resibi_conforme',
                'Nombre_encargado_palata','Nombre_coductor','Nombre_Resibi_conforme','Estado'
            ]);
            $aditivoData['Creado_por'] = $usuarioId;
            $aditivoData['Fecha_creacion'] = now();

            $aditivo = AditivoAplicados::create($aditivoData);

            $vale = ValeDespacho::create([
                'id_Despacho_concreto' => $despacho->id_Despacho_concreto,
                'id_Dosificacion_vale' => $dosificacion->id_Dosificacion_vale,
                'id_Aditivo_aplicados' => $aditivo->id_Aditivo_aplicados,
                'Estado' => 1,
                'Creado_por' => $usuarioId,
                'Fecha_creacion' => now(),
            ]);

            $totalCemento = $this->calcularTotalCemento($dosificacion);

            FormatoControlDespachoPlanta::create([
                'No_envio' => $vale->No_vale,
                'id_Proyecto' => $despacho->id_Proyecto,
                'Tipo_de_Concreto_ps' => $despacho->Tipo_Concreto,
                'Cantidad_Concreto_mT3' => $despacho->Volumen_carga_M3,
                'Concreto_granel_kg' => $dosificacion->kg_cemento_granel,
                'Concreto_sacos_kg' => $dosificacion->Sacos_Cemento,
                'total' => $totalCemento,
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
                'id_Empleados' => $usuarioId,
                'Observaciones' => '',
                'Estado' => 1,
                'Creado_por' => $usuarioId,
                'Fecha_creacion' => now(),
            ]);

            $this->egresarMateriales($dosificacion, $despacho->id_Proyecto);
            $this->egresarAditivos($aditivo, $despacho->id_Proyecto);

            DB::commit();
            return redirect()->route('vale_egreso.index')->with('success', 'Vale egreso creado correctamente.');

        } catch (\Exception $e) {
            DB::rollBack();

            // ⛔️ MOSTRAR ERROR REAL, LÍNEA Y ARCHIVO
            dd(
                "ERROR: " . $e->getMessage(),
                "Línea: " . $e->getLine(),
                "Archivo: " . $e->getFile(),
                "Últimas Querys: ",
                DB::getQueryLog()
            );
        }
    }

    private function obtenerEmpleadoAutenticado()
{
    $usuarioId = Auth::id();

    $empleado = DB::table('tbl_empleados')
        ->where('id_Empleados', $usuarioId)
        ->first();

      if (!$empleado) {
        throw new \Exception('No existe empleado con id = ' . $usuarioId);
    }

    return $empleado;
}

    private function calcularTotalCemento($d)
    {
        $total = 0;
        if ($d->kg_cemento_granel > 0) $total += $d->kg_cemento_granel;
        if ($d->Sacos_Cemento > 0) $total += $d->Sacos_Cemento * 42.5;
        return $total;
    }

    private function egresarMateriales($d, $proyecto)
    {
        $totalCemento = $this->calcularTotalCemento($d);
        if ($totalCemento > 0) {
            $this->descontarDeBodega('cemento', $totalCemento, $proyecto);
        }

        if ($d->kg_piedirn > 0) {
            $m3 = $d->kg_piedirn / 1600;
            $this->descontarDeBodega('piedrin', $m3, $proyecto);
        }

        if ($d->Kg_arena > 0) {
            $m3 = $d->Kg_arena / 1500;
            $this->descontarDeBodega('arena', $m3, $proyecto);
        }

        if ($d->lts_agua > 0) {
            $this->descontarDeBodega('agua', $d->lts_agua, $proyecto);
        }
    }

    private function egresarAditivos($a, $proyecto)
    {
        for ($i = 1; $i <= 4; $i++) {
            $nombre = $a->{'Nombre'.$i};
            $cant = $a->{'Cantidad'.$i};

            if (!empty($nombre) && $cant > 0) {
                $this->descontarDeBodega($nombre, $cant, $proyecto);
            }
        }
    }

    private function descontarDeBodega($nombre, $cantidad, $proyecto)
    {
        $producto = BodegaParaProyectos::where('id_Proyecto', $proyecto)
            ->where('Material', 'LIKE', "%{$nombre}%")
            ->first();

        if (!$producto) {
            throw new \Exception("No existe '{$nombre}' en la bodega del proyecto.");
        }

        if ($producto->Almazenado < $cantidad) {
            throw new \Exception("Stock insuficiente de '{$nombre}' en el proyecto.");
        }

        $producto->Almazenado -= $cantidad;
        $producto->save();

        EstacionBodega::create([
            'material' => $nombre,
            'cantidad' => $cantidad,
            'id_Unidades' => $producto->id_Unidades,
            'proyecto' => $proyecto,
            'Estado' => 1,
            'Creado_por' => Auth::id(),
            'Fecha_creacion' => now(),
            'Observacion' => "Egreso para concreto — Proyecto {$proyecto}"
        ]);
    }
}
