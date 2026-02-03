<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class DetalleNomina extends Model
{
    use HasFactory;

    protected $table = 'tbl_detalle_nomina';
    protected $primaryKey = 'id_detalle_nomina';
    public $timestamps = false;

    protected $fillable = [
        'id_Empleados',
        'Horas_extras',
        'cantidad_dias',
        'totla_A_pagar',
        'Creado_por',
        'Actualizado_por',
        'Fecha_creacion',
        'Fecha_actualizacion'
    ];

public function empleado()
{
    return $this->belongsTo(Empleado::class, 'id_Empleados', 'id_Empleados');
}

public function nomina()
{
    return $this->belongsTo(Nomina::class, 'id_Nomina', 'id_Nomina');
}



    // Método para calcular el total a pagar con JOIN
    public static function calcularTotal($idEmpleado, $horasExtras, $cantidadDias)
    {
        $datos = DB::table('tbl_Empleados as e')
            ->join('tbl_Nomina as n', 'e.id_Nomina', '=', 'n.id_Nomina')
            ->select(
                'n.Costo_horas_extras',
                'n.sueldo_Base',
                'n.viaticosnomina'
            )
            ->where('e.id_Empleados', $idEmpleado)
            ->first();

        if (!$datos) return 0;

        $total = (($horasExtras * $datos->Costo_horas_extras)
                 + $datos->sueldo_Base
                 + $datos->viaticosnomina)
                 * $cantidadDias;

        return $total;
    }
}
