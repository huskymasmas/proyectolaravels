<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ValeIngreso;
use App\Models\BodegaGeneral;
use App\Models\BodegaProyecto;
use App\Models\Unidad;
use App\Models\Configuracion;
use App\Models\Empresa;
use App\Models\Proyecto;
use Illuminate\Support\Facades\Auth;

class ValeIngresoController extends Controller
{
    public function index()
    {
        $vales = ValeIngreso::with('unidad', 'proyecto')->get();
        return view('vale_ingreso.index', compact('vales'));
    }

    public function create()
    {
        $unidades = Unidad::all();
        $empresas = Empresa::all();
        
        return view('vale_ingreso.form', compact('unidades', 'empresas'));
    }

    public function store(Request $request)
    {

            try {

        $request->validate([
            'Fecha' => 'required|date',
            'Hora_llegada' => 'required',
            'Tipo_material' => 'required|string|max:255',
            'Cantidad' => 'required|numeric|min:0',
            'id_Empresa' => 'required|integer',
            'id_Unidades' => 'required|integer|exists:tbl_Unidades,id_Unidades',
            'id_Proyecto' => 'required|integer|exists:tbl_Proyecto,id_Proyecto',
        ]);

        // Crear vale de ingreso
        $vale = new ValeIngreso($request->all());
        $vale->Estado = 1;
        $vale->Creado_por = Auth::id();
        $vale->Actualizado_por = Auth::id();
        $vale->Fecha_creacion = now();
        $vale->Fecha_actualizacion = now();

        // Guardar firmas (si existen)
        foreach (['Firma1', 'Firma2', 'Firma3'] as $key) {
            if ($request->hasFile($key)) {
                $campo = match($key) {
                    'Firma1' => 'Firma1_ruta_imagen_encargado_palata',
                    'Firma2' => 'Firma2_ruta_imagen_bodegero',
                    'Firma3' => 'Firma3_ruta_imagen_residente_obra'
                };
                $vale->$campo = $request->file($key)->store('firmas', 'public');
            }
        }

        $vale->save();

        $bodega = BodegaGeneral::where('Nombre', $request->Tipo_material)->first();
        if ($bodega) {
            $bodega->Cantidad += $request->Cantidad;
            $bodega->Actualizado_por = Auth::id();
            $bodega->Fecha_actualizacion = now();
            $bodega->save();
        } else {
            BodegaGeneral::create([
                'Nombre' => $request->Tipo_material,
                'Descripcion' => $request->Observaciones,
                'Cantidad' => $request->Cantidad,
                'id_Unidades' => $request->id_Unidades,
                'stock_minimo' => 2,
                'Creado_por' => Auth::id(),
                'Actualizado_por' => Auth::id(),
                'Fecha_creacion' => now(),
                'Fecha_actualizacion' => now(),
                'Estado' => 1
            ]);
        }

        // 🔹 OBTENER VALORES DE CONFIGURACIÓN
        $cemento = Configuracion::where('Parametros', 'like', '%cemento%')->value('Valor');
        $arena = Configuracion::where('Parametros', 'like', '%arena%')->value('Valor');
        $piedrin = Configuracion::where('Parametros', 'like', '%piedrín%')->value('Valor');

        $material = strtolower($request->Tipo_material);
        $unidadNombre = strtolower($vale->unidad->Nombre ?? '');
        $cantidad = $request->Cantidad;

        // 🧮 CALCULAR Equivalen y Equivalencia_M3
        $equivalen = 0;
        $equivalencia_m3 = 0;

        if (str_contains($material, 'cemento')) {
            if (str_contains($material, 'saco')) {
                $equivalen = $cantidad;
            } elseif ($unidadNombre == 'kg') {
                $equivalen = $cantidad;
            } else {
                $equivalen = 0;
            }
            $equivalencia_m3 = 0;
        }

        elseif (str_contains($material, 'arena')) {
            if ($unidadNombre == 'kg') {
                $equivalen = $cantidad;
                $equivalencia_m3 = $cantidad / ($arena ?? 1);
            } elseif ($unidadNombre == 'm3') {
                $equivalen = $cantidad * ($arena ?? 0);
                $equivalencia_m3 = $cantidad;
            }
        }

        elseif (str_contains($material, 'piedrin')) {
            if ($unidadNombre == 'kg') {
                $equivalen = $cantidad;
                $equivalencia_m3 = $cantidad / ($piedrin ?? 1);
            } elseif ($unidadNombre == 'm3') {
                $equivalen = $cantidad * ($piedrin ?? 0);
                $equivalencia_m3 = $cantidad;
            }
        }

        elseif (str_contains($material, 'aditivo')) {
            $equivalen = 0;
            $equivalencia_m3 = 0;
        }

        // 🧾 GUARDAR EN tbl_Bodega_proyecto
        BodegaProyecto::create([
            'id_Proyecto' => $request->id_Proyecto,
            'No_vale' => $vale->id_Vale_ingreso,
            'Fecha' => $request->Fecha,
            'Material' => $request->Tipo_material,
            'id_Unidades' => $request->id_Unidades,
            'Cantidad' => $cantidad,
            'Equivalen' => $equivalen,
            'Equivalencia_M3' => $equivalencia_m3,
            'Conductor' => $request->Nombre_coductor,
            'Placa_vehiculo' => $request->Placa_vehiculo,
            'Origen' => $request->Origen_material
        ]);
      

        // ✅ Mensaje de éxito
        return redirect()->route('vale_ingreso.index')
            ->with('success', 'Vale de ingreso registrado y bodegas actualizadas correctamente.');
        } catch (\Exception $e) {
        // ❌ Mensaje de error
        return redirect()->route('vale_ingreso.index')
            ->with('error', 'Ocurrió un error al registrar el vale: ' . $e->getMessage());
      }
    }
}
