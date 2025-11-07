<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ValeDespacho;
use App\Models\DespachoConcreto;
use App\Models\AditivoAplicados;
use App\Models\DosificacionVale;
use App\Models\BodegaGeneral;
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
            // Firmas opcionales
        ]);

        DB::beginTransaction();

        try {
            $usuarioId = Auth::id(); // usuario autenticado

            // 1️⃣ Crear despacho
            $despacho = DespachoConcreto::create(array_merge(
                $request->only([
                    'Codigo_planta','id_Proyecto','Fecha','Volumen_carga_M3',
                    'Hora_salida_plata','Tipo_Concreto','id_Empresa','Inicio_Carga',
                    'Finaliza_carga','Hora_llega_Proyecto','Tipo_elemento',
                    'Placa_numero','Estado','Actualizado_por','Fecha_creacion','Fecha_actualizacion'
                ]),
                ['Creado_por' => $usuarioId] // asignar creado por
            ));

            // 2️⃣ Crear dosificación
            $dosificacion = DosificacionVale::create(array_merge(
                $request->only([
                    'kg_cemento_granel','Sacos_Cemento','kg_piedirn','Kg_arena','lts_agua',
                    'Estado','Actualizado_por','Fecha_creacion','Fecha_actualizacion'
                ]),
                ['Creado_por' => $usuarioId]
            ));

            // 3️⃣ Crear aditivos
            $aditivoData = $request->only([
                'Nombre1','Nombre2','Nombre3','Nombre4',
                'Cantidad1','Cantidad2','Cantidad3','Cantidad4',
                'Firma1_ruta_imagen_encargado_palata','Firma2_ruta_imagen_coductor','Firma3_ruta_imagen_Resibi_conforme',
                'Nombre_encargado_palata','Nombre_coductor','Nombre_Resibi_conforme',
                'Estado','Actualizado_por','Fecha_creacion','Fecha_actualizacion'
            ]);
            $aditivoData['Creado_por'] = $usuarioId;
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

            // 5️⃣ Actualizar bodega
            $this->actualizarBodega('cemento', $dosificacion->kg_cemento_granel + ($dosificacion->Sacos_Cemento * 42.5));
            $this->actualizarBodega('piedrin', $dosificacion->kg_piedirn);
            $this->actualizarBodega('arena', $dosificacion->Kg_arena);
            $this->actualizarBodega('agua', $dosificacion->lts_agua);

            // 6️⃣ Aditivos
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
