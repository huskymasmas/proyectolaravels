<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Exports\FacturaExport;      
use Maatwebsite\Excel\Facades\Excel; 

class FacturaController extends Controller
{
    // 🔹 Mostrar el formulario de búsqueda
    public function index()
    {
        return view('facturas.buscar');
    }

    // 🔹 Buscar registros según Num_factura
    public function buscar(Request $request)
    {
        $numFactura = $request->input('Num_factura');

        // Si no se ingresó nada, simplemente volver a la vista
        if (!$numFactura) {
            return view('facturas.buscar')->with('mensaje', 'Ingrese un número de factura.');
        }

        // 🔸 Consultar los registros de esa factura
        $registros = DB::table('tbl_vale_ingreso')
            ->select('Num_factura', 'nit', 'precio_total', 'cantidad')
            ->where('Num_factura', $numFactura)
            ->get();

        // 🔸 Calcular el total de precio_total
        $total = DB::table('tbl_vale_ingreso')
            ->where('Num_factura', $numFactura)
            ->sum('precio_total');

        // 🔸 Retornar la vista con los resultados
        return view('facturas.buscar', compact('registros', 'total', 'numFactura'));
    }

    public function exportarExcel($numFactura)
{
    return Excel::download(new FacturaExport($numFactura), 'factura_' . $numFactura . '.xlsx');
}
}
