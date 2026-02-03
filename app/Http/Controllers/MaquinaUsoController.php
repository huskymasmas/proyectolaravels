<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\MaquinaUso;
use App\Models\EstacionBodega;
use App\Models\BodegaGeneral;
use App\Models\BodegaParaProyectos;
use App\Models\Proyecto; 

class MaquinaUsoController extends Controller
{
    public function index(){
        $data = MaquinaUso::orderBy('Fecha','desc')->get();
        return view('maquinauso.index', compact('data'));
    }

    public function store(Request $r){
        $r->validate([
            'maquina'=>'required|string',
            'cantidad'=>'required|numeric|min:0.001',
        ]);

        DB::beginTransaction();
        try{
            // El usuario debe indicar origen y origen_id (de dónde toma la máquina)
            $origen = $r->origen; // 'estacion' o 'proyecto'
            $origen_id = $r->origen_id;

            if($origen === 'proyecto') {
                $b = BodegaParaProyectos::find($origen_id);
                if(!$b || ($b->Almazenado ?? 0) < $r->cantidad) throw new \Exception("Stock insuficiente en bodega del proyecto.");
                $b->Almazenado = $b->Almazenado - $r->cantidad;
                $b->Usado = ($b->Usado ?? 0) + $r->cantidad;
                $b->save();
            } else {
                $e = EstacionBodega::find($origen_id);
                if(!$e || ($e->cantidad ?? 0) < $r->cantidad) throw new \Exception("Stock insuficiente en bodega central.");
                $e->cantidad = $e->cantidad - $r->cantidad;
                $e->save();
            }

            MaquinaUso::create([
                'maquina'=>$r->maquina,
                'cantidad'=>$r->cantidad,
                'proyecto'=>$r->proyecto ?? null,
                'Fecha'=>$r->Fecha ?? now()->format('Y-m-d'),
                'Estado'=>1,
                'Creado_por'=>Auth::id(),
                'Fecha_creacion'=>now()->format('Y-m-d'),
                'origen'=>$origen,
                'origen_id'=>$origen_id
            ]);

            DB::commit();
            return redirect()->route('maquinauso.index')->with('success','Uso registrado.');
        } catch(\Exception $e){
            DB::rollBack();
            return back()->withInput()->withErrors(['error'=>$e->getMessage()]);
        }
    }

    // Devolver: elimina registro y devuelve cantidad a su origen
    public function devolver($id){
        DB::beginTransaction();
        try{
            $uso = MaquinaUso::findOrFail($id);
            if($uso->origen === 'proyecto'){
                $b = BodegaParaProyectos::find($uso->origen_id);
                if(!$b) {
                    // crear si no existía
                    $b = BodegaParaProyectos::create([
                        'id_Proyecto'=> null,
                        'Material'=>$uso->maquina,
                        'id_Unidades'=> null,
                        'Cantidad_maxima'=>0,
                        'Usado'=>0,
                        'Almazenado'=>$uso->cantidad,
                        'Estado'=>1,
                        'Creado_por'=>Auth::id(),
                        'Fecha_creacion'=>now()
                    ]);
                } else {
                    $b->Almazenado = ($b->Almazenado ?? 0) + $uso->cantidad;
                    $b->Usado = max(0, ($b->Usado ?? 0) - $uso->cantidad);
                    $b->save();
                }
            } else {
                $e = EstacionBodega::find($uso->origen_id);
                if(!$e){
                    EstacionBodega::create([
                        'material'=>$uso->maquina,
                        'cantidad'=>$uso->cantidad,
                        'id_Unidades'=>null,
                        'proyecto'=>null,
                        'Estado'=>1,
                        'Creado_por'=>Auth::id(),
                        'Fecha_creacion'=>now()->format('Y-m-d')
                    ]);
                } else {
                    $e->cantidad = ($e->cantidad ?? 0) + $uso->cantidad;
                    $e->save();
                }
            }

            $uso->delete();
            DB::commit();
            return redirect()->route('maquinauso.index')->with('success','Devolución realizada.');
        } catch(\Exception $e){
            DB::rollBack();
            return back()->withErrors(['error'=>$e->getMessage()]);
        }
    }
    public function formDevolver($id)
{
    $item = MaquinaUso::findOrFail($id);

    // Bodegas disponibles = proyectos
    $bodegas = Proyecto::orderBy('Nombre')->get();

    return view('maquinauso.formDevolver', compact('item', 'bodegas'));
}

public function procesarDevolucion(Request $request, $id)
{
    $item = MaquinaUso::findOrFail($id);

    $cantidadDevolver = $request->cantidad_devolver;

    if ($cantidadDevolver > $item->cantidad) {
        return back()->with("error", "No puedes devolver más de lo que está en uso.");
    }

    $bodegaDestino = $request->bodega_destino;

    /** REGISTRAR DEVOLUCIÓN A BODEGA */
    if ($bodegaDestino) {
        // Se devuelve a una bodega de proyecto
        $bodega = BodegaParaProyectos::where("Material", $item->maquina)
            ->where("id_Proyecto", $bodegaDestino)
            ->first();

        if (!$bodega) {
            // crear si no existe
            $bodega = BodegaParaProyectos::create([
                "Material" => $item->maquina,
                "Almazenado" => 0,
                "id_Proyecto" => $bodegaDestino,
            ]);
        }

        $bodega->Almazenado += $cantidadDevolver;
        $bodega->save();

        $mensajeBodega = "Se devolvió a la bodega del proyecto seleccionado.";

    } else {
        // Se devuelve a bodega general
        $bodegaGen = BodegaGeneral::where("Nombre", $item->maquina)->first();

        if (!$bodegaGen) {
            $bodegaGen = BodegaGeneral::create([
                "Nombre" => $item->maquina,
                "Cantidad" => 0,
            ]);
        }

        $bodegaGen->Cantidad += $cantidadDevolver;
        $bodegaGen->save();

        $mensajeBodega = "Se devolvió a la bodega general.";
    }

    /** REGISTRAR EN ESTACION BODEGA */
    EstacionBodega::create([
        "material"    => $item->maquina,
        "cantidad"    => $cantidadDevolver,
        "proyecto"    => $mensajeBodega,
        "id_Unidades" => 1,
        "Estado"      => 1
    ]);

    /** ACTUALIZAR O ELIMINAR REGISTRO DE USO */
    $item->cantidad -= $cantidadDevolver;

    if ($item->cantidad == 0) {
        $item->delete();
    } else {
        $item->save();
    }

    return redirect()->route('maquinauso.index')
        ->with("ok", "Devolución procesada correctamente. " . $mensajeBodega);
}

}
