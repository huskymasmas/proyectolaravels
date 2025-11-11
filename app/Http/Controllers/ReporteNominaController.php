<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ReporteNominaController extends Controller
{
    public function index()
    {
        $reportes = DB::select("
            SELECT a.Codigo, a.Nombre, a.Nombre_Rol AS rol,
                   a.Dias_trabajados, a.Total_horas_extras,
                   a.Adelanto_total, a.Adelanto_viaticos, a.Total_Pagos,
                   (a.Dias_trabajados * (a.sueldo_Base + a.Viaticos) + a.Total_horas_extras * a.costos) AS total_devengado,
                   ((a.Dias_trabajados * (a.sueldo_Base + a.Viaticos) + a.Total_horas_extras * a.costos)
                   - (a.Adelanto_total + a.Adelanto_viaticos + a.Total_Pagos)) AS saldo_pendiente
            FROM (
                SELECT 
                    E.Codigo_empleado AS Codigo,
                    CONCAT(COALESCE(E.Nombres, ''), ' ', COALESCE(E.Apellido, ''), ' ', COALESCE(E.Apellido2, '')) AS Nombre,
                    P.Nombre AS Nombre_Rol,
                    COUNT(CASE WHEN R.Trabajo = 'si' THEN 1 END) AS Dias_trabajados,
                    IFNULL(SUM(R.Horas_extras), 0) AS Total_horas_extras,
                    IFNULL(SUM(R.Adelantos), 0) AS Adelanto_total,
                    IFNULL(SUM(R.Adelanto_viatico), 0) AS Adelanto_viaticos,
                    IFNULL(SUM(R.Pago_Parcial), 0) AS Total_Pagos,
                    IFNULL(N.sueldo_Base, 0) AS sueldo_Base,
                    IFNULL(N.viaticosnomina, 0) AS Viaticos,
                    IFNULL(N.Costo_horas_extras, 0) AS costos
                FROM tbl_Empleados E
                INNER JOIN tbl_Nomina N ON E.id_Nomina = N.id_Nomina
                INNER JOIN tbl_Registro_diario R ON E.id_Empleados = R.id_Empleados
                INNER JOIN tbl_Rol P ON E.id_Rol = P.id_Rol
                GROUP BY 
                    E.Codigo_empleado, 
                    E.Nombres, 
                    E.Apellido, 
                    E.Apellido2,
                    P.Nombre,
                    N.sueldo_Base,
                    N.viaticosnomina,
                    N.Costo_horas_extras
            ) AS a
            GROUP BY a.Codigo, a.Nombre, a.Nombre_Rol, a.Dias_trabajados, a.Total_horas_extras, 
                     a.Adelanto_total, a.Adelanto_viaticos, a.Total_Pagos, 
                     a.sueldo_Base, a.Viaticos, a.costos
        ");

        return view('reportes.nomina.index', compact('reportes'));
    }
}
