<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ValeIngresoMaterialesVarios;
use App\Models\BodegaGeneral;
use App\Models\BodegaParaProyectos;
use App\Models\Proyecto;
use App\Models\Unidad;
use App\Models\EstacionBodega;
use App\Models\BodegaProyecto;
use App\Models\Configuracion;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ValeIngresoMaterialesController extends Controller
{
    private $unidadMap = [
        'm3'  => ['m3', 'metro cubico', 'metro cúbico', 'metros cubicos', 'metros cúbicos'],
        'lts' => ['lt', 'lts', 'litros', 'litro', 'l'],
        'saco'=> ['saco', 'sacos', 'bolsa', 'bolsas'],
    ];

    public function index(Request $request)
    {
        $proyectos = Proyecto::all();
        $query = ValeIngresoMaterialesVarios::with(['unidad', 'proyecto']);

        if ($request->filled('id_Proyecto')) {
            if ($request->id_Proyecto === 'bodega_general') {
                $query->whereNull('id_Proyecto');
            } else {
                $query->where('id_Proyecto', $request->id_Proyecto);
            }
        }

        $vales = $query->orderBy('Fecha_ingreso', 'desc')->get();
        return view('vale_ingreso_material.index', compact('vales', 'proyectos'));
    }

    public function create()
    {
        $proyectos = Proyecto::all();
        $unidades = Unidad::all();
        return view('vale_ingreso_material.create', compact('proyectos', 'unidades'));
    }

    private function normalizarUnidadPorNombre(string $unidadNombre): ?string
    {
        $unidadNombre = strtolower(trim($unidadNombre));
        foreach ($this->unidadMap as $canon => $lista) {
            if (in_array($unidadNombre, $lista, true)) {
                return $canon;
            }
        }
        return null;
    }

    private function obtenerNombreUnidadPorId($id)
    {
        return Unidad::where('id_Unidades', $id)->value('Nombre');
    }

    public function store(Request $request)
    {
        $request->validate([
            'Nombre' => 'required|string',
            'id_Unidades' => 'required|integer|exists:tbl_unidades,id_Unidades',
            'cantidad' => 'required|numeric|min:0.0001',
            'costo' => 'nullable|numeric|min:0',
            'Fecha_ingreso' => 'required|date',
        ]);

        $data = $request->only([
            'Nombre','id_Unidades','cantidad','costo','id_Proyecto','Fecha_ingreso',
            'Nombre_conductor','placa','empresa_proveedora','serie','marca',
            'Nombre_encargado','Nombre_Bodeguero','Nombre_Residente_obra',
            'estado_fisico','id_moneda','Num_factura','nit'
        ]);

        $unidadNombre = $this->obtenerNombreUnidadPorId($data['id_Unidades']);
        $unidadCanon = $this->normalizarUnidadPorNombre($unidadNombre ?? '');

        if (!$unidadCanon) {
            return back()->withErrors(['id_Unidades' => 'Unidad de medida no válida o no reconocida.'])->withInput();
        }

        $materialClave = strtolower($data['Nombre']);
        $esCemento = str_contains($materialClave, 'cemento');
        $esArena = str_contains($materialClave, 'arena');
        $esGrava = str_contains($materialClave, 'grava') || str_contains($materialClave, 'piedrin');
        $esAditivo = str_contains($materialClave, 'aditivo') || str_contains($materialClave, 'adesivo') || str_contains($materialClave, 'agua');

        if ($esCemento && $unidadCanon !== 'saco') {
            return back()->withErrors(['id_Unidades' => 'Cemento debe registrarse en sacos.'])->withInput();
        }
        if (($esArena || $esGrava) && $unidadCanon !== 'm3') {
            return back()->withErrors(['id_Unidades' => 'Arena/Grava debe registrarse en m3.'])->withInput();
        }
        if ($esAditivo && $unidadCanon !== 'lts') {
            return back()->withErrors(['id_Unidades' => 'Aditivo debe registrarse en litros.'])->withInput();
        }

        $data['Total_pagar'] = $data['costo'] ?? 0;

        DB::beginTransaction();
        try {

            // =====================================================================
            //  📌 1. GUARDAR EL VALE (NO TOQUÉ NADA)
            // =====================================================================
            $vale = ValeIngresoMaterialesVarios::create(array_merge($data, [
                'Creado_por' => Auth::id(),
                'Actualizado_por' => Auth::id(),
                'Fecha_creacion' => now(),
                'Fecha_actualizacion' => now(),
            ]));

            // =====================================================================
            //  📌 2. LOGICA ORIGINAL DE BODEGA GENERAL / BODEGA PARA PROYECTOS
            // =====================================================================
            if (!empty($data['id_Proyecto'])) {
                $bodega = BodegaParaProyectos::where('id_Proyecto', $data['id_Proyecto'])
                    ->where('Material', $data['Nombre'])
                    ->first();

                $cantidadCanonical = $data['cantidad'];

                if ($bodega) {
                    $nuevoAlmacenado = floatval($bodega->Almazenado) + floatval($cantidadCanonical);
                    if (!is_null($bodega->Cantidad_maxima) && $bodega->Cantidad_maxima > 0 && $nuevoAlmacenado > $bodega->Cantidad_maxima) {
                        DB::rollBack();
                        return back()->withErrors(['cantidad' => 'Se excede la cantidad máxima permitida en la bodega del proyecto.'])->withInput();
                    }

                    $bodega->Almazenado = $nuevoAlmacenado;
                    $bodega->save();
                } else {
                    BodegaParaProyectos::create([
                        'id_Proyecto' => $data['id_Proyecto'],
                        'Material' => $data['Nombre'],
                        'id_Unidades' => $data['id_Unidades'],
                        'Cantidad_maxima' => $data['cantidad'],
                        'Usado' => 0,
                        'Almazenado' => $data['cantidad'],
                        'Estado' => 1,
                    ]);
                }
            } else {
                $bg = BodegaGeneral::where('Nombre', $data['Nombre'])->first();
                if ($bg) {
                    $bg->Cantidad = floatval($bg->Cantidad) + floatval($data['cantidad']);
                    $bg->save();
                } else {
                    BodegaGeneral::create([
                        'Nombre' => $data['Nombre'],
                        'Cantidad' => $data['cantidad'],
                        'id_Unidades' => $data['id_Unidades'],
                        'Estado' => 1,
                    ]);
                }
            }

            EstacionBodega::create([
                'material' => $data['Nombre'],
                'cantidad' => $data['cantidad'],
                'id_Unidades' => $data['id_Unidades'],
                'proyecto' => !empty($data['id_Proyecto']) ? $data['id_Proyecto'] : 'Bodega General',
                'Estado' => 1,
                'Creado_por' => Auth::id(),
                'Fecha_creacion' => now(),
            ]);

            // =====================================================================
            //  📌 3. >>> NUEVO BLOQUE COMPLETO PARA BODEGA_PROYECTO <<<
            // =====================================================================

            if (!empty($data['id_Proyecto'])) {

                $confArena   = Configuracion::where('id_Proyecto', $data['id_Proyecto'])->where('Parametros','like','%arena%')->value('Valor');
                $confCemento = Configuracion::where('id_Proyecto', $data['id_Proyecto'])->where('Parametros','like','%cemento%')->value('Valor');
                $confPiedrin = Configuracion::where('id_Proyecto', $data['id_Proyecto'])->where('Parametros','like','%piedrin%')->value('Valor');

                $u = strtolower($unidadNombre);
                $mat = strtolower($data['Nombre']);
                $cant = floatval($data['cantidad']);

                $equivalen = 0;
                $equiv_m3 = 0;

                // ------------------ CEMENTO ------------------
                if (str_contains($mat, 'cemento')) {
                    if ($u === 'saco') {
                        $equivalen = $cant;
                        $equiv_m3 = 0;
                    }
                }

                // ------------------ ARENA ------------------
                if (str_contains($mat, 'arena')) {
                    if ($u === 'kg' || $u === 'Kilogramo') {
                        $equivalen = $cant;
                        $equiv_m3 = $cant / ($confArena ?: 1);
                    } elseif ($u === 'm3' || $u === 'Metro cúbico') {
                        $equivalen = $cant * ($confArena ?: 1);
                        $equiv_m3 = $cant;
                    }
                }

                // ------------------ PIEDRIN ------------------
                if (str_contains($mat, 'piedrin') || str_contains($mat,'grava')) {
                    if ($u === 'kg' || $u === 'Kilogramo') {
                        $equivalen = $cant;
                        $equiv_m3 = $cant / ($confPiedrin ?: 1);
                    } elseif ($u === 'm3' || $u === 'Metro cúbico') {
                        $equivalen = $cant * ($confPiedrin ?: 1);
                        $equiv_m3 = $cant;
                    }
                }

                // ------------------ ADITIVO ------------------
                if (str_contains($mat,'aditivo') || str_contains($mat,'adesivo') || str_contains($mat,'agua')) {
                    $equivalen = 0;
                    $equiv_m3 = 0;
                }

                BodegaProyecto::create([
                    'id_Proyecto' => $data['id_Proyecto'],
                    'No_vale' => $vale->id_Vale_ingreso ,
                    'Fecha' => $data['Fecha_ingreso'],
                    'Material' => $data['Nombre'],
                    'id_Unidades' => $data['id_Unidades'],
                    'Cantidad' => $cant,
                    'Equivalen' => $equivalen,
                    'Equivalencia_M3' => $equiv_m3,
                    'Conductor' => $data['Nombre_conductor'] ?? null,
                    'Placa_vehiculo' => $data['placa'] ?? null,
                    'Origen' => 'Ingresado con Vale Ingreso Materiales'
                ]);
            }

            // =====================================================================

            DB::commit();
            return redirect()->route('vale_ingreso_material.index')
                ->with('success', 'Ingreso registrado correctamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Error al registrar el ingreso: ' . $e->getMessage()])
                ->withInput();
        }
    }
}
