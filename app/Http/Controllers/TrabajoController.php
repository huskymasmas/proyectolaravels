<?php

namespace App\Http\Controllers;

use App\Models\Trabajo;
use App\Models\Aldea;
use App\Models\EstadoTrabajo;
use App\Models\Unidad;
use App\Models\Plano;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TrabajoExport;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

class TrabajoController extends Controller
{
    /** Listar */
 public function index(Request $request)
{
    $aldeas = Aldea::all();
    $trabajos = Trabajo::with(['aldea', 'estadoTrabajo', 'unidad', 'planos'])
                        ->when($request->aldea, function($q) use ($request) {
                            $q->where('id_aldea', $request->aldea);
                        })
                        ->get();

    // Traer planos de la aldea seleccionada
    $planosAldea = [];
    if ($request->aldea) {
        $planosAldea = Plano::where('id_aldea', $request->aldea)->get();
    }

    return view('trabajo.index', compact('aldeas', 'trabajos', 'planosAldea'));
}


    /** Crear */
    public function create()
    {
        $aldeas = Aldea::all();
        $estados = EstadoTrabajo::all();
        $unidades = Unidad::all();

        return view('trabajo.form', compact('aldeas', 'estados', 'unidades'));
    }

    /** Guardar */
    public function store(Request $request)
    {
        $request->validate([
            'id_aldea' => 'required',
            'numero_face' => 'required',
            'nombre_face' => 'required',
            'id_Estado_trabajo' => 'required',
            'cantidad' => 'required|numeric',
            'id_Unidades' => 'required',
            'CostoQ' => 'required|numeric',
        ]);

        $subtotal = $request->cantidad * $request->CostoQ;

        $trabajo = Trabajo::create([
            'id_aldea' => $request->id_aldea,
            'numero_face' => $request->numero_face,
            'nombre_face' => $request->nombre_face,
            'id_Estado_trabajo' => $request->id_Estado_trabajo,
            'cantidad' => $request->cantidad,
            'id_Unidades' => $request->id_Unidades,
            'CostoQ' => $request->CostoQ,
            'Subtotal' => $subtotal,
            'estado' => 1,
            'creado_por' => Auth::id(),
            'fecha_creacion' => now(),
        ]);

        /** Guardar planos PDF */
        if ($request->hasFile('planos')) {
            foreach ($request->file('planos') as $file) {
                Plano::create([
                    'nombre' => $file->getClientOriginalName(),
                    'datos' => file_get_contents($file->getRealPath()),
                    'id_aldea' => $request->id_aldea,
                    'id_trabajo' => $trabajo->id_trabajos,
                    'Creado_por' => Auth::id(),
                    'Fecha_creacion' => now(),
                ]);
            }
        }

        return redirect()->route('trabajo.index')
            ->with('success', 'Trabajo registrado correctamente.');
    }

    /** Editar */
    public function edit($id)
    {
        $trabajo = Trabajo::with('planos')->findOrFail($id);
        $aldeas = Aldea::all();
        $estados = EstadoTrabajo::all();
        $unidades = Unidad::all();

        return view('trabajo.form', compact('trabajo', 'aldeas', 'estados', 'unidades'));
    }

    /** Actualizar */
    public function update(Request $request, $id)
    {
        $request->validate([
            'id_aldea' => 'required',
            'numero_face' => 'required',
            'nombre_face' => 'required',
            'id_Estado_trabajo' => 'required',
            'cantidad' => 'required|numeric',
            'id_Unidades' => 'required',
            'CostoQ' => 'required|numeric',
        ]);

        $trabajo = Trabajo::findOrFail($id);

        $trabajo->update([
            'id_aldea' => $request->id_aldea,
            'numero_face' => $request->numero_face,
            'nombre_face' => $request->nombre_face,
            'id_Estado_trabajo' => $request->id_Estado_trabajo,
            'cantidad' => $request->cantidad,
            'id_Unidades' => $request->id_Unidades,
            'CostoQ' => $request->CostoQ,
            'Subtotal' => $request->cantidad * $request->CostoQ,
            'actualizado_por' => Auth::id(),
            'fecha_actualizacion' => now(),
        ]);

        /** Guardar nuevos planos */
        if ($request->hasFile('planos')) {
            foreach ($request->file('planos') as $file) {
                Plano::create([
                    'nombre' => $file->getClientOriginalName(),
                    'datos' => file_get_contents($file->getRealPath()),
                    'id_aldea' => $request->id_aldea,
                    'id_trabajo' => $trabajo->id_trabajos,
                    'Creado_por' => Auth::id(),
                    'Fecha_creacion' => now(),
                ]);
            }
        }

        return redirect()->route('trabajo.index')
            ->with('success', 'Trabajo actualizado correctamente.');
    }

    /** Exportar Excel */
    public function exportExcel(Request $request)
    {
        return Excel::download(new TrabajoExport($request->aldea), 'trabajos.xlsx');
    }

    /** Exportar PDF con anexos */
    public function exportPdf(Request $request)
    {
        $query = Trabajo::with(['aldea', 'estadoTrabajo', 'unidad', 'planos']);

        if ($request->filled('aldea')) {
            $query->where('id_aldea', $request->aldea);
        }

        $trabajos = $query->get();

        $pdf = PDF::loadView('trabajo.pdf', compact('trabajos'));

        return $pdf->download('trabajos.pdf');
    }
}
