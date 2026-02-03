<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Nomina;
use App\Models\DetalleNomina;
use App\Models\Empleado;

class ReporteNominaController extends Controller
{
    /* ============================================================
     * INDEX PRINCIPAL – Incluye todas las tablas
     * ============================================================ */
    public function index(Request $request)
    {
        $empleado = $request->empleado;
        $search = $request->Nombres ?? '';

        $empleados = DB::select("
            SELECT id_Empleados,
            CONCAT(Nombres,' ',Apellido,' ',Apellido2) AS nombre
            FROM tbl_empleados
        ");

        $filtro = $empleado ? " WHERE E.id_empleados = $empleado " : "";

        // TABLA 1: REPORTE GENERAL
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
                    CONCAT(E.Nombres,' ',E.Apellido,' ',E.Apellido2) AS Nombre,
                    P.Nombre AS Nombre_Rol,
                    COUNT(CASE WHEN R.Trabajo = 'si' THEN 1 END) AS Dias_trabajados,
                    IFNULL(SUM(R.Horas_extras), 0) AS Total_horas_extras,
                    IFNULL(SUM(R.Adelantos), 0) AS Adelanto_total,
                    IFNULL(SUM(R.Adelanto_viatico), 0) AS Adelanto_viaticos,
                    IFNULL(SUM(R.Pago_Parcial), 0) AS Total_Pagos,
                    IFNULL(N.sueldo_Base, 0) AS sueldo_Base,
                    IFNULL(N.viaticosnomina, 0) AS Viaticos,
                    IFNULL(N.Costo_horas_extras, 0) AS costos
                FROM tbl_empleados E
                INNER JOIN tbl_nomina N ON E.id_Nomina = N.id_Nomina
                INNER JOIN tbl_registro_diario R ON E.id_Empleados = R.id_Empleados
                INNER JOIN tbl_rol P ON E.id_Rol = P.id_Rol
                $filtro
                GROUP BY 
                    E.Codigo_empleado, E.Nombres, E.Apellido, E.Apellido2,
                    P.Nombre, N.sueldo_Base, N.viaticosnomina, N.Costo_horas_extras
            ) AS a
        ");

        // TABLA 2: DETALLE POR EMPLEADO
        $detalles = DB::select("
            SELECT 
                E.Codigo_empleado, E.Nombres, E.Apellido, E.Apellido2,
                IFNULL(N.sueldo_base, 0) AS sueldo_base,
                IFNULL(N.viaticosnomina, 0) AS viaticosnomina, 
                IFNULL(E.Estado_trabajo, 0) AS Estado_trabajo,
                IFNULL(D.Horas_extras, 0) AS Horas_extras,
                IFNULL(N.Costo_horas_extras, 0) AS Costo_horas_extras,
                IFNULL(D.cantidad_dias, 0) AS cantidad_dias,
                IFNULL(D.totla_A_pagar, 0) AS total_a_pagar
            FROM tbl_empleados E
            INNER JOIN tbl_nomina N ON E.id_Nomina = N.id_Nomina
            LEFT JOIN tbl_detalle_nomina D ON E.id_Empleados = D.id_Empleados
            $filtro
        ");

        // TABLA 3: SOLO NOMINAS (con acciones)
        $tablaNomina = DB::select("
            SELECT 
                N.id_Nomina,
                E.Codigo_empleado AS Codigo,
                CONCAT(E.Nombres,' ',E.Apellido,' ',E.Apellido2) AS Nombre,
                N.sueldo_Base,
                N.viaticosnomina,
                N.Costo_horas_extras,
                N.Estado,
                N.Fecha_creacion
            FROM tbl_nomina N
            LEFT JOIN tbl_empleados E ON E.id_Nomina = N.id_Nomina
            ORDER BY N.id_Nomina DESC
        ");

        // TABLA 4: SOLO DETALLE NOMINA (con acciones)
        $tablaDetalle = DB::select("
            SELECT 
                D.id_detalle_nomina,
                D.id_Empleados,
                E.Codigo_empleado AS Codigo,
                CONCAT(E.Nombres,' ',E.Apellido,' ',E.Apellido2) AS Nombre,
                D.Horas_extras,
                D.cantidad_dias,
                D.totla_A_pagar,
                D.Fecha_creacion
            FROM tbl_detalle_nomina D
            LEFT JOIN tbl_empleados E ON E.id_Empleados = D.id_Empleados
            ORDER BY D.id_detalle_nomina DESC
        ");

        return view('reportes.nomina.index', compact(
            'reportes', 'detalles', 'empleados', 'empleado', 'search', 'tablaNomina', 'tablaDetalle'
        ));
    }

    /* ============================================================
     * CREATE NÓMINA
     * ============================================================ */
    public function create()
    {
        $empleados = DB::table('tbl_empleados')
            ->select('id_Empleados', DB::raw("CONCAT(Nombres,' ',Apellido,' ',Apellido2) AS nombre"))
            ->get();

        return view('reportes.nomina.create', compact('empleados'));
    }

    /* ============================================================
     * STORE NÓMINA
     * ============================================================ */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'sueldo_Base' => 'required|numeric|min:0',
            'Costo_horas_extras' => 'required|numeric|min:0',
            'Bonos' => 'nullable|numeric|min:0',
            'Bonos_adicional' => 'nullable|numeric|min:0',
            'viaticosnomina' => 'nullable|numeric|min:0',
            'Estado' => 'required|in:0,1',
        ]);

        $validated['Creado_por'] = Auth::id();
        $validated['Fecha_creacion'] = now();

        Nomina::create($validated);

        return redirect()->route('reportes.nomina.index')->with('success', 'Nómina creada correctamente.');
    }

    /* ============================================================
     * EDIT NÓMINA
     * ============================================================ */
    public function edit($id)
    {
        $nomina = DB::table('tbl_Nomina')->where('id_Nomina', $id)->first();

        if (!$nomina) {
            return back()->with('error', 'Nómina no encontrada.');
        }

        return view('reportes.nomina.edit', compact('nomina'));
    }

    /* ============================================================
     * UPDATE NÓMINA
     * ============================================================ */
    public function update(Request $request, $id)
    {
        $request->validate([
            'sueldo_Base' => 'required|numeric|min:0',
            'Costo_horas_extras' => 'required|numeric|min:0',
            'viaticosnomina' => 'nullable|numeric|min:0',
            'Estado' => 'required|in:0,1'
        ]);

        DB::table('tbl_Nomina')->where('id_Nomina', $id)->update([
            'sueldo_Base' => $request->sueldo_Base,
            'Costo_horas_extras' => $request->Costo_horas_extras,
            'viaticosnomina' => $request->viaticosnomina,
            'Estado' => $request->Estado,
            'Actualizado_por' => Auth::id(),
            'Fecha_actualizacion' => now()
        ]);

        return redirect()->route('reportes.nomina.index')->with('success', 'Nómina actualizada correctamente.');
    }

    /* ============================================================
     * DESTROY NÓMINA
     * ============================================================ */
    public function destroy($id)
    {
        DB::table('tbl_Nomina')->where('id_Nomina', $id)->delete();
        return redirect()->route('reportes.nomina.index')->with('success', 'Nómina eliminada correctamente.');
    }

    /* ============================================================
     * DETALLE CREATE
     * ============================================================ */
    public function detalleCreate()
    {
        $empleados = DB::table('tbl_empleados')
            ->select('id_Empleados', DB::raw("CONCAT(Nombres,' ',Apellido,' ',Apellido2) AS nombre"))
            ->get();

        $nominas = Nomina::orderBy('id_Nomina', 'DESC')->get();

        return view('reportes.nomina.detalle_create', compact('empleados','nominas'));
    }

    /* ============================================================
     * DETALLE STORE
     * ============================================================ */
    public function guardarDetalle(Request $request)
    {
        $request->validate([
            'id_Empleados' => 'required|integer|exists:tbl_empleados,id_Empleados',
            'Horas_extras' => 'required|numeric|min:0',
            'cantidad_dias' => 'required|integer|min:0'
        ]);

        $empleado = DB::table('tbl_empleados')
            ->where('id_Empleados', $request->id_Empleados)
            ->first();

        if (!$empleado) {
            return back()->with('error', 'Empleado no encontrado.');
        }

        $nomina = DB::table('tbl_nomina')
            ->where('id_Nomina', $empleado->id_Nomina)
            ->first();

        if (!$nomina) {
            return back()->with('error', 'El empleado no tiene nómina asignada.');
        }

        $totalPagar = ($request->cantidad_dias * ($nomina->sueldo_Base / 30)) + ($request->Horas_extras * $nomina->Costo_horas_extras);

        DB::table('tbl_detalle_nomina')->insert([
            'id_Empleados' => $request->id_Empleados,
            'Horas_extras' => $request->Horas_extras,
            'cantidad_dias' => $request->cantidad_dias,
            'totla_A_pagar' => $totalPagar,
            'id_Nomina' => $empleado->id_Nomina,
            'Creado_por' => Auth::id(),
            'Fecha_creacion' => now()
        ]);

        return redirect()->back()->with('success', 'Detalle guardado correctamente.');
    }

    /* ============================================================
     * DETALLE EDIT
     * ============================================================ */
    public function detalleEdit($id)
    {
        $detalle = DB::table('tbl_detalle_nomina')->where('id_detalle_nomina', $id)->first();
        if (!$detalle) {
            return back()->with('error', 'Detalle no encontrado.');
        }
        return view('reportes.nomina.detalle_edit', compact('detalle'));
    }

    /* ============================================================
     * DETALLE UPDATE
     * ============================================================ */
    public function detalleUpdate(Request $request, $id)
    {
        $request->validate([
            'Horas_extras' => 'required|numeric|min:0',
            'cantidad_dias' => 'required|integer|min:0'
        ]);

        DB::table('tbl_detalle_nomina')->where('id_detalle_nomina', $id)->update([
            'Horas_extras' => $request->Horas_extras,
            'cantidad_dias' => $request->cantidad_dias,
            'Actualizado_por' => Auth::id(),
            'Fecha_actualizacion' => now()
        ]);

        return redirect()->route('reportes.nomina.index')->with('success', 'Detalle actualizado correctamente.');
    }

    /* ============================================================
     * DETALLE DESTROY
     * ============================================================ */
    public function detalleDestroy($id)
    {
        DB::table('tbl_detalle_nomina')->where('id_detalle_nomina', $id)->delete();
        return redirect()->route('reportes.nomina.index')->with('success', 'Detalle eliminado correctamente.');
    }
}
