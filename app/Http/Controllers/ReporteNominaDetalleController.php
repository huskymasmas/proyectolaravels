<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ReporteNominaDetalleController extends Controller
{
    public function index()
    {
        $detalles = DB::select("
            SELECT 
                E.Codigo_empleado, 
                E.Nombres, 
                E.Apellido, 
                E.Apellido2, 
                IFNULL(N.sueldo_base, 0) AS sueldo_base, 
                IFNULL(N.viaticosnomina, 0) AS viaticosnomina, 
                IFNULL(E.Estado_trabajo, 0) AS Estado_trabajo, 
                IFNULL(D.Horas_extras, 0) AS Horas_extras, 
                IFNULL(N.Costo_horas_extras, 0) AS Costo_horas_extras, 
                IFNULL(D.cantidad_dias, 0) AS cantidad_dias, 
                IFNULL(D.totla_A_pagar, 0) AS total_a_pagar
            FROM tbl_Empleados E 
            INNER JOIN tbl_Nomina N ON E.id_Nomina = N.id_Nomina 
            LEFT JOIN tbl_detalle_nomina D ON E.id_Empleados = D.id_Empleados
        ");

        return view('reportes.nomina_detalle_empleados.index', compact('detalles'));
    }
}
