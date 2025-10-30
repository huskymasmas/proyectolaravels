<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\produccion;
use App\Models\Dosificacion;
use App\Models\Planta;
use App\Models\Proyecto;
use Carbon\Carbon;
use DB;

class ProduccionController extends Controller
{
 // Método create
  public function create()
{
    $dosificaciones = Dosificacion::where('Estado',1)->get();
    $plantas = Planta::where('Estado',1)->get();
    $proyectos = Proyecto::where('Estado',1)->get();
    $producciones = Produccion::with(['dosificacion','planta','proyecto'])->get();

    return view('produccion.create', compact('dosificaciones','plantas','proyectos','producciones'));
}


    // Mostrar formulario de edición
    public function edit($id)
    {
        $produccion = Produccion::findOrFail($id);
        return view('produccion.edit', compact('produccion'));
    }

    // Actualizar registro
    public function update(Request $request, $id)
    {
        $produccion = Produccion::findOrFail($id);
        $produccion->update($request->all()); // usa $fillable
        return redirect()->route('produccion.create')->with('success', 'Producción actualizada correctamente.');
    }
    // Método store
   public function store(Request $request)
{
    $request->validate([
        'id_Proyecto' => 'required|exists:tbl_Proyecto,id_Proyecto',
        'id_Dosificacion' => 'required|exists:tbl_Dosificacion,id_Dosificacion',
        'id_Planta' => 'required|exists:tbl_Planta,id_Planta',
        'Fecha' => 'required|date',
        'Volumen_m3' => 'required|numeric',
        'Cemento_kg' => 'required|numeric',
        'Arena_m3' => 'required|numeric',
        'Piedrin_m3' => 'required|numeric',
        'Aditivo_l' => 'required|numeric',
        'id_Vale' => 'nullable|numeric',
    ]);

    Produccion::create($request->all());

    return redirect()->route('produccion.create')->with('success', 'Mezcla registrada correctamente.');
}



public function reporte(Request $request)
{
    $proyectos = Proyecto::select('id_Proyecto', 'Nombre')->get();
    $plantas = Planta::select('id_Planta', 'Nombre')->get();
    $proyectos = Proyecto::select('id_Proyecto', 'Nombre')->get();
    $plantas = Planta::select('id_Planta', 'Nombre')->get();


    $desde = $request->input('desde', Carbon::now()->startOfMonth()->toDateString());
    $hasta = $request->input('hasta', Carbon::now()->endOfMonth()->toDateString());
    $id_planta = $request->input('id_planta');

    // Consulta optimizada con solo columnas necesarias
    $registros = Produccion::select(
        'id_Produccion','id_Proyecto','id_Planta','id_Dosificacion',
        'Volumen_m3','Cemento_kg','Arena_m3','Piedrin_m3','Aditivo_l','id_Vale','Fecha'
    )
    ->with([
        'dosificacion:id_Dosificacion,Cemento,Arena,Pedrin,Aditivo',
        'planta:id_Planta,Nombre',
        'proyecto:id_Proyecto,Nombre'
    ])
    ->whereBetween('Fecha', [$desde, $hasta])
    ->when($id_planta, fn($q) => $q->where('id_Planta', $id_planta))
    ->paginate(50); // Paginación obligatoria

    // Totales globales
    $totalesGlobales = Produccion::selectRaw("
        SUM(Volumen_m3) as total_m3,
        SUM(Cemento_kg) as total_cemento,
        SUM(Arena_m3) as total_arena,
        SUM(Piedrin_m3) as total_piedrin,
        SUM(Aditivo_l) as total_aditivo
    ")
    ->whereBetween('Fecha', [$desde, $hasta])
    ->when($id_planta, fn($q) => $q->where('id_Planta', $id_planta))
    ->first();

    // Total horas m3/h desde tbl_Vale
    $totalesHoras = DB::table('tbl_Vale')
        ->join('tbl_Produccion', 'tbl_Produccion.id_Vale', '=', 'tbl_Vale.id_Vale')
        ->whereBetween('tbl_Produccion.Fecha', [$desde, $hasta])
        ->when($id_planta, fn($q) => $q->where('tbl_Produccion.id_Planta', $id_planta))
        ->selectRaw("SUM(TIMESTAMPDIFF(MINUTE,inicio_descarga,Finalizacion_descarga)) as total_minutos")
        ->first();

    $totalHours = $totalesHoras->total_minutos ? $totalesHoras->total_minutos / 60 : 0;
    $m3PerHour = $totalHours > 0 ? $totalesGlobales->total_m3 / $totalHours : null;

    return view('produccion.reporte', [
        'registros' => $registros, 
        'totales' => [
            'total_m3' => $totalesGlobales->total_m3,
            'total_hours' => $totalHours,
            'm3_per_hour' => $m3PerHour,
            'consumo_real' => [
                'Cemento_kg' => $totalesGlobales->total_cemento,
                'Arena_m3' => $totalesGlobales->total_arena,
                'Piedrin_m3' => $totalesGlobales->total_piedrin,
                'Aditivo_l' => $totalesGlobales->total_aditivo,
            ],
        ],
        'desde' => $desde,
        'hasta' => $hasta,
        'proyectos' =>  $proyectos ,
         'plantas' => $plantas,
    ]);
}
}