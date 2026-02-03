<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asistencia extends Model
{
    use HasFactory;

    protected $table = 'tbl_Asistencia';
    protected $primaryKey = 'id_Asistencia';
    public $timestamps = false;

    protected $fillable = [
        'id_Empleados',
        'Fecha',
        'Hora_ingreso',
        'Hora_finalizacion',
        'Estado',
        'Creado_por',
        'Actualizado_por',
        'Fecha_creacion',
        'Fecha_actualizacion'
    ];

    public function empleado()
    {
        return $this->belongsTo(Empleado::class, 'id_Empleados', 'id_Empleados');
    }
}
