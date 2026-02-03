<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Plano;

class PlanoController extends Controller
{
    /**
     * Guardar plano por TRABAJO
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_trabajo' => 'required|exists:tbl_trabajos,id_trabajos',
            'nombre'     => 'required|string|max:255',
            'plano'      => 'required|file|mimes:pdf,jpg,jpeg,png'
        ]);

        $file = $request->file('plano');
        $filename = time() . '_' . $file->getClientOriginalName();
        $file->move(public_path('planos/trabajos'), $filename);

        Plano::create([
            'nombre'     => $request->nombre,
            'datos'      => $filename,
            'id_trabajo' => $request->id_trabajo,
        ]);

        return back()->with('success', 'Plano agregado al trabajo.');
    }

    /**
     * Guardar plano por ALDEA
     */
    public function storePorAldea(Request $request)
    {
        $request->validate([
            'id_aldea' => 'required|exists:tbl_aldea,id_aldea',
            'nombre'   => 'required|string|max:255',
            'plano'    => 'required|file|mimes:pdf,jpg,jpeg,png'
        ]);

        $file = $request->file('plano');
        $filename = time() . '_' . $file->getClientOriginalName();
        $file->move(public_path('planos/aldeas'), $filename);

        Plano::create([
            'nombre'   => $request->nombre,
            'datos'    => $filename,
            'id_aldea' => $request->id_aldea,
        ]);

        return back()->with('success', 'Plano agregado para la Aldea.');
    }

    /**
     * Ver archivo PDF/Imagen
     */
   public function ver($id)
{
    $plano = DB::table('tbl_planos')->where('id_planos', $id)->first();

    if (!$plano) {
        abort(404, 'Plano no encontrado');
    }

    $pathTrabajo = public_path('planos/trabajos/' . $plano->datos);
    $pathAldea   = public_path('planos/aldeas/' . $plano->datos);

    if (file_exists($pathTrabajo)) {
        return response()->file($pathTrabajo);
    }

    if (file_exists($pathAldea)) {
        return response()->file($pathAldea);
    }

    abort(404, 'Archivo no encontrado');
}

}
