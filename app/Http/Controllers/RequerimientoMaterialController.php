<?php

namespace App\Http\Controllers;

use App\Models\RequerimientoMaterial;
use App\Models\Dosificacion;
use App\Models\DetalleObra;
use App\Models\Configuracion;
use App\Models\Proyecto;
use App\Models\Material;
use App\Models\Tipo_dosificador ;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class RequerimientoMaterialController extends Controller
{

   public function index(Request $request)
   {   
      $proyectos = Proyecto::all();
    $idProyecto = $request->id_Proyecto;

    // Si se selecciona un proyecto, filtramos por ese ID
    $requerimientos = RequerimientoMaterial::with(['dosificacion', 'detalleObra'])
        ->when($idProyecto, function ($query) use ($idProyecto) {
            $query->whereHas('detalleObra', function ($subquery) use ($idProyecto) {
                $subquery->where('id_Proyecto', $idProyecto);
            });
        })
        ->get();

    return view('requerimientos.index', compact('proyectos', 'requerimientos', 'idProyecto'));
}

    public function create()
    {
    $proyectos = Proyecto::all();
    $dosificaciones = Dosificacion::all();
    $detallesObra = DetalleObra::all();
    $Tipo_dosificador = Tipo_dosificador::all();

    return view('requerimientos.form', compact('proyectos', 'dosificaciones', 'detallesObra' ,'Tipo_dosificador'));
    }

    public function store(Request $request)
    {
        $idProyecto = $request->id_Proyecto;

        // Buscar configuraciones del proyecto actual
        $configSacoCemento = Configuracion::where('id_Proyecto', $idProyecto)
            ->where('Parametros', 'like', '%Peso saco cemento%')
            ->first();

        if (!$configSacoCemento) {
            return response()->json(['error' => 'No se encontró la configuración de peso del saco de cemento.'], 404);
        }
        $configDensidadarena = Configuracion::where('id_Proyecto', $idProyecto)
            ->where('Parametros', 'like', '%Densidad arena%')
            ->first();

        if (!$configDensidadarena) {
            return response()->json(['error' => 'No se encontró la configuración de Densidad arena '], 404);
        }

        $configDensidadpiedrin = Configuracion::where('id_Proyecto', $idProyecto)
            ->where('Parametros', 'like', '%Densidad piedrín%')
            ->first();

        if (!$configDensidadpiedrin) {
            return response()->json(['error' => 'No se encontró la configuración de Densidad piedrín'], 404);
        }


        // Buscar dosificación y detalle obra asociados al proyecto
        $dosificacion = Dosificacion::findOrFail($request->id_Dosificacion);
        $detalleObra = DetalleObra::findOrFail($request->id_Detalle_obra);


        $tipo = $detalleObra->nombre_detalle; 
        $resultado = $detalleObra->Resultado ?? 1;

        // Calcular materiales
        $cementoKg = $resultado * $dosificacion->Cemento;
        $cementoSacos = $cementoKg / $configSacoCemento->Valor;

        $arenaM3 = $resultado * $dosificacion->Arena;
        $arenaKg = $arenaM3 * $configDensidadarena->Valor; // ejemplo, podrías buscar densidad arena en configuración

        $piedrinM3 = $resultado * $dosificacion->Pedrin;
        print($piedrinM3);
        $piedrinKg = $piedrinM3 * $configDensidadpiedrin->Valor;

        $aditivoL = $resultado * $dosificacion->Aditivo;

        // Guardar requerimiento
        $req = new RequerimientoMaterial();
        $req->id_Dosificacion = $dosificacion->id_Dosificacion;
        $req->id_Detalle_obra = $detalleObra->id_Detalle_obra;
        $req->Cemento_kg = $cementoKg;
        $req->Cemento_sacos = $cementoSacos;
        $req->Arena_m3 = $arenaM3;
        $req->Arena_kg = $arenaKg;
        $req->Piedrin_m3 = $piedrinM3;
        $req->Piedrin_kg = $piedrinKg;
        $req->Aditivo_l = $aditivoL;
        $req->Creado_por = Auth::id();
        $req->Fecha_creacion = Carbon::now();
        $req->save();


         return redirect()->route('requerimientos.index')->with('success', 'requerimientos creada correctamente.');
    }
}
