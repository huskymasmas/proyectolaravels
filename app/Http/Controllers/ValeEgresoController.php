<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ValeDespacho;
use App\Models\DespachoConcreto;
use App\Models\AditivoAplicados;
use App\Models\DosificacionVale;
use App\Models\BodegaParaProyectos;
use App\Models\FormatoControlDespachoPlanta;
use App\Models\EstacionBodega;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ValeEgresoController extends Controller
{
    public function index()
    {
        $vales = ValeDespacho::with(['despacho.proyecto','despacho.empresa','dosificacion','aditivo'])->get();
        return view('vale_egreso.index', compact('vales'));
    }

    public function create()
    {
        $proyectos = \App\Models\Proyecto::all();
        $empresas  = \App\Models\Empresa::all();
        return view('vale_egreso.create', compact('proyectos','empresas'));
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

            DB::enableQueryLog();

            $usuarioId = Auth::id();

            /**
             * OBTENER EMPLEADO REAL (NO USER)
             */
            $idEmpleado = DB::table('tbl_empleados')
                ->orderBy('id_Empleados')
                ->value('id_Empleados');

            if (!$idEmpleado) {
                throw new \Exception('No existen empleados registrados');
            }

            /**
             * DESPACHO
             */
            $despacho = DespachoConcreto::create(array_merge(
                $request->only([
                    'Codigo_planta','id_Proyecto','Fecha','Volumen_carga_M3',
                    'Hora_salida_plata','Tipo_Concreto','id_Empresa','Inicio_Carga',
                    'Finaliza_carga','Hora_llega_Proyecto','Tipo_elemento',
                    'Placa_numero','Estado'
                ]),
                ['Fecha_creacion'=>now()]
            ));

            /**
             * DOSIFICACION
             */
            $dosificacion = DosificacionVale::create(array_merge(
                $request->only([
                    'kg_cemento_granel','Sacos_Cemento','kg_piedirn','Kg_arena','lts_agua','Estado'
                ]),
                ['Fecha_creacion'=>now()]
            ));

            /**
             * ADITIVOS
             */
            $aditivoData = $request->only([
                'Nombre1','Nombre2','Nombre3','Nombre4',
                'Cantidad1','Cantidad2','Cantidad3','Cantidad4',
                'Firma1_ruta_imagen_encargado_palata',
                'Firma2_ruta_imagen_coductor',
                'Firma3_ruta_imagen_Resibi_conforme',
                'Nombre_encargado_palata',
                'Nombre_coductor',
                'Nombre_Resibi_conforme',
                'Estado'
            ]);

            $aditivoData['Creado_por'] = $usuarioId;
            $aditivoData['Fecha_creacion'] = now();

            $aditivo = AditivoAplicados::create($aditivoData);

            /**
             * VALE
             */
            $vale = ValeDespacho::create([
                'id_Despacho_concreto'=>$despacho->id_Despacho_concreto,
                'id_Dosificacion_vale'=>$dosificacion->id_Dosificacion_vale,
                'id_Aditivo_aplicados'=>$aditivo->id_Aditivo_aplicados,
                'Estado'=>1,
                'Fecha_creacion'=>now(),
            ]);

            /**
             * FORMATO CONTROL (AQUI ESTABA EL ERROR)
             */
            FormatoControlDespachoPlanta::create([
                'No_envio'=>$vale->No_vale,
                'id_Proyecto'=>$despacho->id_Proyecto,
                'Tipo_de_Concreto_ps'=>$despacho->Tipo_Concreto,
                'Cantidad_Concreto_mT3'=>$despacho->Volumen_carga_M3,
                'Concreto_granel_kg'=>$dosificacion->kg_cemento_granel,
                'Concreto_sacos_kg'=>$dosificacion->Sacos_Cemento,
                'total'=>$this->calcularTotalCemento($dosificacion),
                'kg_Piedrin'=>$dosificacion->kg_piedirn,
                'kg_Arena'=>$dosificacion->Kg_arena,
                'Lts_Agua'=>$dosificacion->lts_agua,
                'Aditivo1'=>$aditivo->Nombre1,
                'Aditivo2'=>$aditivo->Nombre2,
                'Aditivo3'=>$aditivo->Nombre3,
                'Aditivo4'=>$aditivo->Nombre4,
                'cantidad1'=>$aditivo->Cantidad1,
                'cantidad2'=>$aditivo->Cantidad2,
                'cantidad3'=>$aditivo->Cantidad3,
                'cantidad4'=>$aditivo->Cantidad4,
                'id_Empleados'=>$idEmpleado,   // ← CORREGIDO
                'Observaciones'=>'',
                'Estado'=>1,
                'Creado_por'=>$usuarioId,
                'Fecha_creacion'=>now(),
            ]);

            $this->egresarMateriales($dosificacion,$despacho->id_Proyecto);
            $this->egresarAditivos($aditivo,$despacho->id_Proyecto);

            DB::commit();

            return redirect()->route('vale_egreso.index')->with('success','Vale creado');

        } catch (\Exception $e) {

            DB::rollBack();

            dd(
                $e->getMessage(),
                DB::getQueryLog()
            );
        }
    }

    private function calcularTotalCemento($d)
    {
        return ($d->kg_cemento_granel ?? 0) + (($d->Sacos_Cemento ?? 0) * 42.5);
    }

    private function egresarMateriales($d,$proyecto)
    {
        if($d->kg_cemento_granel>0 || $d->Sacos_Cemento>0)
            $this->descontarDeBodega('cemento',$this->calcularTotalCemento($d),$proyecto);

        if($d->kg_piedirn>0)
            $this->descontarDeBodega('piedrin',$d->kg_piedirn/1600,$proyecto);

        if($d->Kg_arena>0)
            $this->descontarDeBodega('arena',$d->Kg_arena/1500,$proyecto);

        if($d->lts_agua>0)
            $this->descontarDeBodega('agua',$d->lts_agua,$proyecto);
    }

    private function egresarAditivos($a,$proyecto)
    {
        for($i=1;$i<=4;$i++){
            $n=$a->{'Nombre'.$i};
            $c=$a->{'Cantidad'.$i};
            if($n && $c>0) $this->descontarDeBodega($n,$c,$proyecto);
        }
    }

 private function descontarDeBodega($nombre,$cantidad,$proyecto)
{
    $producto = BodegaParaProyectos::where('id_Proyecto',$proyecto)
        ->where('Material','LIKE',"%{$nombre}%")
        ->first();

    if(!$producto){
        throw new \Exception("NO EXISTE '{$nombre}' EN BODEGA DEL PROYECTO {$proyecto}");
    }

    if($producto->Almazenado < $cantidad){
        throw new \Exception("STOCK INSUFICIENTE DE '{$nombre}'");
    }

    $producto->Almazenado -= $cantidad;
    $producto->save();

    EstacionBodega::create([
        'material'=>$nombre,
        'cantidad'=>$cantidad,
        'id_Unidades'=>$producto->id_Unidades,
        'proyecto'=>$proyecto,
        'Estado'=>1,
        'Creado_por'=>Auth::id(),
        'Fecha_creacion'=>now(),
        'Observacion'=>"Egreso automático"
    ]);
}

}
