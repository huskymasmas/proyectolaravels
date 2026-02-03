<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AvanceTrabajo;
use App\Models\Trabajo;
use App\Models\Aldea;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AvanceTrabajoController extends Controller
{
    public function index()
    {
    $avances = \DB::table('tbl_avances_trabajo AS a')
        ->join('tbl_trabajos AS t', 't.id_trabajos', '=', 'a.id_trabajos')
        ->select(
            'a.*',
            't.nombre_face AS nombre_trabajo'
        )
        ->get();

    return view('avances.index', compact('avances'));
    }

    /**
     * FORMULARIO PARA CREAR AVANCE
     */
    public function create()
    {
        $aldeas = Aldea::all();
        return view('avances.create', compact('aldeas'));
    }

    /**
     * AJAX - OBTENER TRABAJOS POR ALDEA
     */
    public function getTrabajos($id_aldea)
    {
        $trabajos = Trabajo::where('id_aldea', $id_aldea)
            ->select('id_trabajos', 'nombre_face', 'cantidad', 'CostoQ')
            ->get();

        return response()->json($trabajos);
    }

    /**
     * GUARDAR AVANCE
     */
public function store(Request $request)
{
    $request->validate([
        'id_aldea'    => 'required|integer',
        'id_trabajos' => 'required|integer',
        'Cantidad'    => 'required|numeric|min:0.01',
    ]);

    DB::beginTransaction();

    try {
        // Usar Eloquent para obtener el trabajo (no DB::table -> stdClass)
        $trabajo = Trabajo::find($request->id_trabajos);

        if (!$trabajo) {
            throw new \Exception("FK error: tbl_trabajos.id_trabajos={$request->id_trabajos} no existe.");
        }

        // Normalizar nombre de campo para cantidad y costo (por si están con mayúsculas)
        $cantidadDisponible = null;
        $costoDisponible = null;

        if (isset($trabajo->cantidad)) {
            $cantidadDisponible = $trabajo->cantidad;
        } elseif (isset($trabajo->Cantidad)) {
            $cantidadDisponible = $trabajo->Cantidad;
        } elseif (isset($trabajo->CANTIDAD)) {
            $cantidadDisponible = $trabajo->CANTIDAD;
        }

        if (isset($trabajo->CostoQ)) {
            $costoDisponible = $trabajo->CostoQ;
        } elseif (isset($trabajo->costoQ)) {
            $costoDisponible = $trabajo->costoQ;
        } elseif (isset($trabajo->costo_q)) {
            $costoDisponible = $trabajo->costo_q;
        }

        // Si no encontramos la columna cantidad, fallamos con mensaje claro
        if ($cantidadDisponible === null) {
            throw new \Exception("Columna 'cantidad' no encontrada en tbl_trabajos para id {$request->id_trabajos}. Revisa el nombre real de la columna.");
        }

        // Validaciones de negocio
        if ((float)$cantidadDisponible <= 0) {
            throw new \Exception("Stock error: cantidad disponible en trabajo <= 0");
        }
        if ((float)$request->Cantidad > (float)$cantidadDisponible) {
            throw new \Exception("Stock error: cantidad solicitada ({$request->Cantidad}) mayor que disponible ({$cantidadDisponible})");
        }

        // Preparar datos para insertar (usar toDateString para DATE)
        $data = [
            'id_aldea'            => (int)$request->id_aldea,
            'id_trabajos'         => (int)$request->id_trabajos,
            'Cantidad'            => $request->Cantidad,
            'Estado'              => 1,
            'Creado_por'          => Auth::id() ?? 0,
            'Actualizado_por'     => Auth::id() ?? 0,
            'Fecha_creacion'      => now()->toDateString(),
            'Fecha_actualizacion' => now()->toDateString(),
        ];

        // Intentar crear con Eloquent
        $avance = AvanceTrabajo::create($data);

        // Si create devolvió falsy, intentar con query builder para ver el error real
        if (!$avance || !($avance instanceof AvanceTrabajo)) {
            try {
                $insertId = \DB::table('tbl_avances_trabajo')->insertGetId($data);
                // actualizar trabajo después del insert manual
                $created = \DB::table('tbl_avances_trabajo')->where('id_avances_trabajo', $insertId)->first();
            } catch (\Throwable $e2) {
                DB::rollBack();
                throw new \Exception("Error insertando (query builder): " . $e2->getMessage());
            }
        }

        // Calculos para actualizar trabajo (usar las variables normalizadas)
        $cantidadOriginal = (float)$cantidadDisponible;
        $costoOriginal = is_null($costoDisponible) ? 0 : (float)$costoDisponible;
        $cantidadAvanzada = (float)$request->Cantidad;

        $costoDescontado = 0;
        if ($costoOriginal > 0 && $cantidadOriginal > 0) {
            $costoUnitario = $costoOriginal / $cantidadOriginal;
            $costoDescontado = $costoUnitario * $cantidadAvanzada;
        }

        // Actualizar usando el modelo Eloquent (asegura nombres correctos)
        // detectar nombre real de los campos en el modelo Trabajo
        if (property_exists($trabajo, 'cantidad')) {
            $trabajo->cantidad = $cantidadOriginal - $cantidadAvanzada;
        } elseif (property_exists($trabajo, 'Cantidad')) {
            $trabajo->Cantidad = $cantidadOriginal - $cantidadAvanzada;
        } else {
            // si no existe como propiedad, asignar usando attributes
            $trabajo->setAttribute('cantidad', $cantidadOriginal - $cantidadAvanzada);
        }

        if ($costoOriginal > 0) {
            if (property_exists($trabajo, 'CostoQ')) {
                $trabajo->CostoQ = $costoOriginal - $costoDescontado;
            } elseif (property_exists($trabajo, 'costoQ')) {
                $trabajo->costoQ = $costoOriginal - $costoDescontado;
            } else {
                $trabajo->setAttribute('CostoQ', $costoOriginal - $costoDescontado);
            }
        }

        // actualizaciones de auditoría y fecha
        // usar nombres de columna en minúscula tal como tu modelo Trabajo define (si usa actualizado_por / fecha_actualizacion)
        if (property_exists($trabajo, 'actualizado_por')) {
            $trabajo->actualizado_por = Auth::id() ?? 0;
        } else {
            $trabajo->setAttribute('actualizado_por', Auth::id() ?? 0);
        }

        if (property_exists($trabajo, 'fecha_actualizacion')) {
            $trabajo->fecha_actualizacion = now()->toDateString();
        } else {
            $trabajo->setAttribute('fecha_actualizacion', now()->toDateString());
        }

        $trabajo->save();

        DB::commit();

        // éxito (quitá dd después)
        return redirect()->route('avances.index')->with('success', 'Avance registrado correctamente.');

    } catch (\Throwable $e) {
        DB::rollBack();

        // Mensaje diagnóstico que te devolverá el error exacto
        $prechecks = [
            'request' => $request->all(),
            'trabajo_raw' => isset($trabajo) ? (is_object($trabajo) ? (array)$trabajo : $trabajo) : null,
        ];

        return dd([
            'ok' => false,
            'error_message' => $e->getMessage(),
            'prechecks' => $prechecks,
        ]);
    }
}


}
