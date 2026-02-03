<?php

namespace App\Http\Controllers;

use App\Models\ValeIngresoMaterialesVarios;
use App\Models\ValeEgresoMaterialesVarios;
use App\Models\BodegaParaProyectos;
use App\Models\BodegaGeneral;
use App\Models\EstacionBodega;
use App\Models\BodegaProyecto;
use App\Models\Configuracion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ValeMaterialesController extends Controller
{
    public function storeIngreso(Request $request)
    {
        $data = $request->all();

        // Guardar ingreso
        $vale = ValeIngresoMaterialesVarios::create($data);

        // Calcular equivalencias
        $material = strtolower($vale->Nombre);
        $unidadNombre = strtolower($vale->unidad->Nombre ?? '');
        $cantidad = $vale->cantidad;

        $cemento = Configuracion::where('Parametros', 'like', '%cemento%')->value('Valor');
        $arena = Configuracion::where('Parametros', 'like', '%arena%')->value('Valor');
        $piedrin = Configuracion::where('Parametros', 'like', '%piedrín%')->value('Valor');

        $equivalen = 0;
        $equivalencia_m3 = 0;

        if (str_contains($material, 'cemento')) {
            $equivalen = ($unidadNombre == 'kg' || str_contains($material,'saco')) ? $cantidad : 0;
            $equivalencia_m3 = 0;
        } elseif (str_contains($material, 'arena')) {
            if ($unidadNombre == 'kg') {
                $equivalen = $cantidad;
                $equivalencia_m3 = $cantidad / ($arena ?? 1);
            } elseif ($unidadNombre == 'm3') {
                $equivalen = $cantidad * ($arena ?? 0);
                $equivalencia_m3 = $cantidad;
            }
        } elseif (str_contains($material, 'piedrin')) {
            if ($unidadNombre == 'kg') {
                $equivalen = $cantidad;
                $equivalencia_m3 = $cantidad / ($piedrin ?? 1);
            } elseif ($unidadNombre == 'm3') {
                $equivalen = $cantidad * ($piedrin ?? 0);
                $equivalencia_m3 = $cantidad;
            }
        } elseif (str_contains($material, 'aditivo')) {
            $equivalen = 0;
            $equivalencia_m3 = 0;
        }

        // 🔹 Registrar en bodega y estación
        if (!empty($data['id_Proyecto'])) {
            // Proyecto
            BodegaParaProyectos::create([
                'id_Proyecto' => $data['id_Proyecto'],
                'Material' => $data['Nombre'],
                'id_Unidades' => $data['id_Unidades'],
                'Cantidad_maxima' => $data['cantidad'],
                'Usado' => 0,
                'Almazenado' => $data['cantidad'],
                'Estado' => 1
            ]);

            BodegaProyecto::create([
                'id_Proyecto' => $data['id_Proyecto'],
                'No_vale' => $vale->id_vale_equipo_maquinaria_vehiculo,
                'Fecha' => $vale->Fecha_ingreso,
                'Material' => $data['Nombre'],
                'id_Unidades' => $data['id_Unidades'],
                'Cantidad' => $data['cantidad'],
                'Equivalen' => $equivalen,
                'Equivalencia_M3' => $equivalencia_m3,
                'Origen' => 'Ingreso a proyecto',
                'Conductor' => $data['Nombre_conductor'] ?? '',
                'Placa_vehiculo' => $data['placa'] ?? ''
            ]);
        } else {
            // Bodega general
            BodegaGeneral::create([
                'Nombre' => $data['Nombre'],
                'Cantidad' => $data['cantidad'],
                'id_Unidades' => $data['id_Unidades'],
                'Estado' => 1
            ]);
        }

        // Registrar en estación de bodega
        EstacionBodega::create([
            'material' => $data['Nombre'],
            'cantidad' => $data['cantidad'],
            'id_Unidades' => $data['id_Unidades'],
            'proyecto' => $data['id_Proyecto'] ?? 'Bodega general',
            'Estado' => 1
        ]);

        return response()->json(['message' => 'Ingreso registrado correctamente']);
    }

    public function storeEgreso(Request $request)
    {
        $data = $request->all();

        // Solo egreso desde proyecto
        if (empty($data['id_Proyecto'])) {
            return response()->json(['error' => 'El egreso solo se permite desde bodegas de proyecto'], 400);
        }

        // Guardar egreso
        $vale = ValeEgresoMaterialesVarios::create($data);

        // Actualizar bodega proyecto
        $bodega = BodegaParaProyectos::where('id_Proyecto', $data['id_Proyecto'])
            ->where('Material', $data['Nombre'])
            ->first();

        if ($bodega) {
            $bodega->Almazenado -= $data['cantidad'];
            $bodega->save();
        }

        // Calcular equivalencias
        $material = strtolower($data['Nombre']);
        $unidadNombre = strtolower($bodega->unidad->Nombre ?? '');
        $cantidad = $data['cantidad'];

        $cemento = Configuracion::where('Parametros', 'like', '%cemento%')->value('Valor');
        $arena = Configuracion::where('Parametros', 'like', '%arena%')->value('Valor');
        $piedrin = Configuracion::where('Parametros', 'like', '%piedrín%')->value('Valor');

        $equivalen = 0;
        $equivalencia_m3 = 0;

        if (str_contains($material, 'cemento')) {
            $equivalen = ($unidadNombre == 'kg' || str_contains($material,'saco')) ? $cantidad : 0;
            $equivalencia_m3 = 0;
        } elseif (str_contains($material, 'arena')) {
            if ($unidadNombre == 'kg') {
                $equivalen = $cantidad;
                $equivalencia_m3 = $cantidad / ($arena ?? 1);
            } elseif ($unidadNombre == 'm3') {
                $equivalen = $cantidad * ($arena ?? 0);
                $equivalencia_m3 = $cantidad;
            }
        } elseif (str_contains($material, 'piedrin')) {
            if ($unidadNombre == 'kg') {
                $equivalen = $cantidad;
                $equivalencia_m3 = $cantidad / ($piedrin ?? 1);
            } elseif ($unidadNombre == 'm3') {
                $equivalen = $cantidad * ($piedrin ?? 0);
                $equivalencia_m3 = $cantidad;
            }
        } elseif (str_contains($material, 'aditivo')) {
            $equivalen = 0;
            $equivalencia_m3 = 0;
        }

        // Registrar movimiento en bodega proyecto
        BodegaProyecto::create([
            'id_Proyecto' => $data['id_Proyecto'],
            'No_vale' => $vale->id_vale_egreso_Materiales_varios,
            'Fecha' => $vale->Fecha,
            'Material' => $data['Nombre'],
            'id_Unidades' => $data['id_Unidades'],
            'Cantidad' => $data['cantidad'],
            'Equivalen' => $equivalen,
            'Equivalencia_M3' => $equivalencia_m3,
            'Origen' => 'Egreso de proyecto',
            'Conductor' => $data['Nombre_conductor'] ?? '',
            'Placa_vehiculo' => $data['placa'] ?? ''
        ]);

        // Registrar en estación de bodega
        EstacionBodega::create([
            'material' => $data['Nombre'],
            'cantidad' => $data['cantidad'],
            'id_Unidades' => $data['id_Unidades'],
            'proyecto' => $data['id_Proyecto'],
            'Estado' => 1
        ]);

        return response()->json(['message' => 'Egreso registrado correctamente']);
    }
}
