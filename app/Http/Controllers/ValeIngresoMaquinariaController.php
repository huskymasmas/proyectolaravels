<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\ValeIngresoMaquinaria;
use App\Models\EstacionBodega;
use App\Models\BodegaParaProyectos;
use App\Models\BodegaGeneral;
use App\Models\Moneda;

class ValeIngresoMaquinariaController extends Controller
{
    public function index(){
         $data = ValeIngresoMaquinaria::with('proyecto')
             ->orderBy('Fecha_ingreso','desc')
             ->get();
        $data = ValeIngresoMaquinaria::orderBy('Fecha_ingreso','desc')->get();
        return view('valeingres_maquinaria.index', compact('data'));
    }

    public function create(){
        $proyectos = DB::table('tbl_proyecto')->get();
        $unidades = DB::table('tbl_unidades')->get();
        $monedas = Moneda::where('Estado',1)->get();
        return view('valeingres_maquinaria.form',
            compact('proyectos','unidades','monedas'));
    }

    public function edit($id){
        $item = ValeIngresoMaquinaria::findOrFail($id);
        $proyectos = DB::table('tbl_Proyecto')->get();
        $unidades = DB::table('tbl_Unidades')->get();
        $monedas = Moneda::where('Estado',1)->get();
        return view('valeingres_maquinaria.form',
            compact('item','proyectos','unidades','monedas'));
    }

    public function destroy($id){
        ValeIngresoMaquinaria::findOrFail($id)->delete();
        return back()->with('success','Registro eliminado.');
    }

    /* ============================================================
       STORE (crear)
    ============================================================ */
    public function store(Request $r){

        $validated = $r->validate([
            'Nombre'=>'required|string',
            'cantidad'=>'required|numeric|min:0.001',
            'id_moneda'=>'required|integer'
        ]);

        DB::beginTransaction();
        try {

            $ingreso = ValeIngresoMaquinaria::create([
                'Nombre'=>$r->Nombre,
                'Nombre_encargado'=>$r->Nombre_encargado,
                'Nombre_Bodeguero'=>$r->Nombre_Bodeguero,
                'marca'=>$r->marca,
                'serie'=>$r->serie,
                'placa'=>$r->placa,
                'Fecha_ingreso'=>$r->Fecha_ingreso ?? now()->format('Y-m-d'),
                'Hora_llegada'=>$r->Hora_llegada ?? now()->format('H:i:s'),
                'empresa_proveedora'=>$r->empresa_proveedora,
                'cantidad'=>$r->cantidad,
                'estado_fisico'=>$r->estado_fisico,
                'costo'=>$r->costo,
                'id_moneda'=>$r->id_moneda,
                'Num_factura'=>$r->Num_factura,
                'nit'=>$r->nit,
                'Estado'=>1,
                'Creado_por'=>Auth::id(),
                'Fecha_creacion'=>now(),
                'id_Proyecto'=>$r->id_Proyecto ?? null,
            ]);

            /* ============================================================
               BODEGA PARA PROYECTOS (SI HAY PROYECTO)
            ============================================================ */
            if($r->id_Proyecto){

                $bodegaP = BodegaParaProyectos::where('id_Proyecto',$r->id_Proyecto)
                    ->where('Material',$r->Nombre)
                    ->where('id_Unidades',$r->id_Unidades)
                    ->first();

                if($bodegaP){
                    $bodegaP->Almazenado += $r->cantidad;
                    $bodegaP->Actualizado_por = Auth::id();
                    $bodegaP->Fecha_actualizacion = now();
                    $bodegaP->save();
                } else {
                    BodegaParaProyectos::create([
                        'id_Proyecto'=>$r->id_Proyecto,
                        'Material'=>$r->Nombre,
                        'Cantidad_maxima'=>0,
                        'Usado'=>0,
                        'Almazenado'=>$r->cantidad,
                        'id_Unidades'=>$r->id_Unidades,
                        'Estado'=>1,
                        'Creado_por'=>Auth::id(),
                        'Fecha_creacion'=>now()
                    ]);
                }

            } else {

                /* ============================================================
                   BODEGA GENERAL (SI NO HAY PROYECTO)
                ============================================================ */

                $bg = BodegaGeneral::where('Nombre',$r->Nombre)
                    ->where('id_Unidades',$r->id_Unidades)
                    ->first();

                if($bg){
                    $bg->Cantidad += $r->cantidad;
                    $bg->Actualizado_por = Auth::id();
                    $bg->Fecha_actualizacion = now();
                    $bg->save();
                } else {
                    BodegaGeneral::create([
                        'Nombre'=>$r->Nombre,
                        'Descripcion'=>'Ingreso desde Vale Maquinaria',
                        'Cantidad'=>$r->cantidad,
                        'id_Unidades'=>$r->id_Unidades,
                        'stock_minimo'=>0,
                        'Estado'=>1,
                        'Creado_por'=>Auth::id(),
                        'Fecha_creacion'=>now()
                    ]);
                }
            }

            /* ============================================================
               ESTACIÓN BODEGA
            ============================================================ */
            $nombreProyecto = null;

            if ($r->id_Proyecto) {
            $nombreProyecto = DB::table('tbl_Proyecto')
                    ->where('id_Proyecto', $r->id_Proyecto)
                    ->value('Nombre');
       }

            EstacionBodega::create([
                'material'=>$r->Nombre,
                'cantidad'=>$r->cantidad,
                'proyecto'=> $r->id_Proyecto ? 
                    "El {$r->Nombre} va para el proyecto {$nombreProyecto}" 
                    : "Material enviado a Bodega General",
                'Estado'=>1,
                'Creado_por'=>Auth::id(),
                'Fecha_creacion'=>now()->format('Y-m-d')
            ]);

            DB::commit();
            return redirect()->route('valeingres_maquinaria.index')
                ->with('success','✔ Registro guardado correctamente');

        } catch(\Exception $e){
            DB::rollBack();
            return back()->with('error',"Error: ".$e->getMessage());
        }
    }

    /* ============================================================
       UPDATE (editar)
    ============================================================ */
    public function update(Request $r, $id){

        $validated = $r->validate([
            'Nombre'=>'required|string',
            'cantidad'=>'required|numeric|min:0.001',
            'id_moneda'=>'required|integer'
        ]);

        DB::beginTransaction();
        try {

            $item = ValeIngresoMaquinaria::findOrFail($id);

            $item->update($r->all() + [
                'Actualizado_por'=>Auth::id(),
                'Fecha_actualizacion'=>now()
            ]);

            /* ============================================================
               IGUAL QUE EN STORE: PROYECTO O BODEGA GENERAL
            ============================================================ */
            
            if($r->id_Proyecto){

                $bodega = BodegaParaProyectos::where('id_Proyecto',$r->id_Proyecto)
                    ->where('Material',$r->Nombre)
                    ->where('id_Unidades',$r->id_Unidades)
                    ->first();

                if($bodega){
                    $bodega->Almazenado = $r->cantidad;
                    $bodega->Actualizado_por = Auth::id();
                    $bodega->Fecha_actualizacion = now();
                    $bodega->save();
                }

            } else {

                $bg = BodegaGeneral::where('Nombre',$r->Nombre)
                    ->where('id_Unidades',$r->id_Unidades)
                    ->first();

                if($bg){
                    $bg->Cantidad = $r->cantidad;
                    $bg->Actualizado_por = Auth::id();
                    $bg->Fecha_actualizacion = now();
                    $bg->save();
                }
            }

            DB::commit();
            return redirect()->route('valeingres_maquinaria.index')
                ->with('success','✔ Registro actualizado correctamente');

        } catch(\Exception $e){
            DB::rollBack();
            return back()->with('error',"Error: ".$e->getMessage());
        }
    }
}
