<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\BodegaGeneral;
use App\Models\BodegaParaProyectos;
use App\Models\BodegaProyecto;
use App\Models\EstacionBodega;
use App\Models\Unidad;

class TransferenciaController extends Controller
{
    /**
     * Transferir materiales desde BodegaGeneral a BodegaParaProyectos
     */
    public function index()
    {
    $bodegasGenerales = BodegaGeneral::where('Estado', 1)->get();
    $bodegasProyectos = BodegaParaProyectos::where('Estado', 1)->get();
    $transferencias = EstacionBodega::orderBy('Fecha_creacion','desc')->get();

    return view('transferencias.index', compact('bodegasGenerales', 'bodegasProyectos', 'transferencias'));
    }

    public function transferir(Request $request)
    {
        $request->validate([
            'id_bodega_general' => 'required|exists:tbl_bodega_general,id_Bodega_general',
            'id_bodega_proyecto' => 'required|exists:tbl_bodega_para_proyectos,id_Bodega_para_proyectos',
            'cantidad' => 'required|numeric|min:0.001',
        ]);

        $bodegaGeneral = BodegaGeneral::findOrFail($request->id_bodega_general);
        $bodegaProyecto = BodegaParaProyectos::findOrFail($request->id_bodega_proyecto);

        // Verificar si las unidades coinciden
        if ($bodegaGeneral->id_Unidades != $bodegaProyecto->id_Unidades) {
            // Aquí puedes colocar la lógica de conversión según tus reglas de negocio
            $conversion = $this->convertirUnidad($bodegaGeneral->cantidad, $bodegaGeneral->id_Unidades, $bodegaProyecto->id_Unidades);
        } else {
            $conversion = $request->cantidad;
        }

        // Validar cantidad máxima
        $cantidadDisponible = $bodegaProyecto->Cantidad_maxima - $bodegaProyecto->Almazenado;
        if ($conversion > $cantidadDisponible) {
            return back()->with('error', 'La cantidad supera el límite máximo permitido para este proyecto.');
        }

        // Actualizar BodegaParaProyectos
        $bodegaProyecto->Almazenado += $conversion;
        $bodegaProyecto->save();

        // Registrar en EstacionBodega
        EstacionBodega::create([
            'material' => $bodegaGeneral->Nombre,
            'cantidad' => $conversion,
            'id_Unidades' => $bodegaProyecto->id_Unidades,
            'proyecto' => $bodegaProyecto->id_Proyecto,
            'Estado' => 1
        ]);
        $ultimoVale = BodegaProyecto::max('No_vale'); // obtiene el mayor No_vale
        $nuevoVale = $ultimoVale ? $ultimoVale + 1 : 1; // si no hay vale, empieza en 1

        // Registrar en BodegaProyecto
        BodegaProyecto::create([
            'id_Proyecto' => $bodegaProyecto->id_Proyecto,
            'No_vale' => $nuevoVale,
            'Fecha' => now(),
            'Material' => $bodegaGeneral->Nombre,
            'id_Unidades' => $bodegaProyecto->id_Unidades,
            'Cantidad' => $conversion,
            'Equivalen' => $conversion, // aquí puedes aplicar tu cálculo de equivalencia si aplica
            'Equivalencia_M3' => null,
            'Conductor' => null,
            'Placa_vehiculo' => null,
            'Origen' => 'Bodega General'
        ]);

        // Reducir cantidad en BodegaGeneral
        $bodegaGeneral->Cantidad -= $request->cantidad;
        $bodegaGeneral->save();

        return back()->with('success', 'Transferencia realizada correctamente.');
    }

    /**
     * Método de conversión de unidades (ejemplo simple)
     */
    private function convertirUnidad($cantidad, $unidadOrigen, $unidadDestino)
    {
        // Aquí debes implementar tu tabla de conversiones
        // Por ejemplo: kg a toneladas, litros a m3, etc.
        // Esto es solo un ejemplo
        $factor = 1; // default sin conversión
        // Ejemplo: si unidadOrigen = 1 (kg) y unidadDestino = 2 (tonelada)
        // $factor = 0.001;

        return $cantidad * $factor;
    }
}
