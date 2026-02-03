<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ValeEgreso_Maquinaria;
use App\Models\Proyecto;
use App\Models\MaquinaUso;
use App\Models\EstacionBodega;
use App\Models\BodegaGeneral;
use App\Models\BodegaParaProyectos;

class ValeEgreso_MaquinariaController extends Controller
{
    public function index(Request $request)
    {
        $proyectos = Proyecto::orderBy('Nombre')->get();
        $idProyecto = $request->id_Proyecto;

        $data = ValeEgreso_Maquinaria::when($idProyecto, fn($q) =>
            $q->where('id_Proyecto', $idProyecto)
        )->orderBy('Fecha', 'DESC')->get();

        return view('valeegreso_maquinaria.index', compact('data', 'proyectos', 'idProyecto'));
    }

    public function create()
    {
        $proyectos = Proyecto::orderBy('Nombre')->get();
        $item = null;

        return view('valeegreso_maquinaria.create', compact('item', 'proyectos'));
    }

    public function edit($id)
    {
        $item = ValeEgreso_Maquinaria::findOrFail($id);
        $proyectos = Proyecto::orderBy('Nombre')->get();

        return view('valeegreso_maquinaria.edit', compact('item', 'proyectos'));
    }

    public function store(Request $request)
    {
        $maquina     = $request->Nombre;
        $cantidad    = $request->cantidad;
        $idProyecto  = $request->id_Proyecto;

        /** ----------------------------------------------
         *   VALIDACIÓN DE STOCK ANTES DE CREAR EL VALE
         *  ---------------------------------------------- */

        if ($idProyecto != null) {

            $bodegaProyecto = BodegaParaProyectos::where("Material", $maquina)
                ->where("id_Proyecto", $idProyecto)
                ->first();

            if (!$bodegaProyecto)
                return back()->with("error", "La maquinaria no existe en el proyecto.");

            if ($bodegaProyecto->Almazenado < $cantidad)
                return back()->with("error", "Cantidad insuficiente en el proyecto. Stock actual: " . $bodegaProyecto->Almazenado);

        } else {

            $bodegaGeneral = BodegaGeneral::where("Nombre", $maquina)->first();

            if (!$bodegaGeneral)
                return back()->with("error", "La maquinaria no existe en bodega general.");

            if ($bodegaGeneral->Cantidad < $cantidad)
                return back()->with("error", "Cantidad insuficiente en bodega general. Stock actual: " . $bodegaGeneral->Cantidad);
        }

        /** ----------------------------------------------------
         *  SI LLEGA AQUÍ, HAY STOCK SUFICIENTE → AHORA SÍ CREA
         * ---------------------------------------------------- */

        $vale = ValeEgreso_Maquinaria::create($request->all());

        /** ----------------------------------------------
         *   DESCUENTO DE STOCK (MISMO CÓDIGO ORIGINAL)
         *  ---------------------------------------------- */

        if ($idProyecto != null) {

            $bodegaProyecto->Almazenado -= $cantidad;
            $bodegaProyecto->save();

            $nombreProyecto = Proyecto::find($idProyecto)->Nombre_Proyecto;
            $textoProyecto = $nombreProyecto . " - EN USO";

        } else {

            $bodegaGeneral->Cantidad -= $cantidad;
            $bodegaGeneral->save();

            $textoProyecto = "SIN PROYECTO - EN USO";
        }

        /** Registros adicionales */
        EstacionBodega::create([
            "material"    => $maquina,
            "cantidad"    => $cantidad,
            "proyecto"    => $textoProyecto,
            "id_Unidades" => 1,
            "Estado"      => 1
        ]);

        MaquinaUso::create([
            "maquina"          => $maquina,
            "cantidad"         => $cantidad,
            "proyecto"         => $idProyecto ? $nombreProyecto : "SIN PROYECTO",
            "Fecha"            => now(),
            "Fecha_desuso"     => null,
            "Estado"           => 1
        ]);

        return redirect()->route('valeegreso_maquinaria.index')
            ->with("ok", "Egreso registrado correctamente.");
    }


    public function update(Request $request, $id)
    {
        $item = ValeEgreso_Maquinaria::findOrFail($id);
        $item->update($request->all());

        return redirect()->route('valeegreso_maquinaria.index')
            ->with("ok", "Vale actualizado correctamente.");
    }

    public function destroy($id)
    {
        ValeEgreso_Maquinaria::findOrFail($id)->delete();

        return back()->with("ok", "Vale eliminado correctamente.");
    }
}
