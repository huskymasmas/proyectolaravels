<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Nomina extends Model
{
    protected $table = 'tbl_Nomina';
    protected $primaryKey = 'id_Nomina';
    public $timestamps = false;

    protected $fillable = [
        'salario_base',
        'bono',
        'bono_adicional',
        'descuentos',
        'Descuento_IGSS',
        'Descuento_ISR',
        'Descuento_IRTRA',
        'total_pago',
        'Estado',
        'Creado_por',
        'Actualizado_por',
        'Fecha_creacion',
        'Fecha_actualizacion'
    ];

    // Relación con empleados
    public function empleados()
    {
        return $this->hasMany(Empleado::class, 'id_Nomina');
    }
}
