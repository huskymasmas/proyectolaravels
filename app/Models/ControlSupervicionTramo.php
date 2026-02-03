<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ControlSupervicionTramo extends Model
{
    use HasFactory;

    protected $table = 'tbl_Control_supervicion_Tramo';
    protected $primaryKey = 'id_Control_supervicion_Tramo';
    public $timestamps = false;

    protected $fillable = [
        'Fecha', 'No_envio', 'Tipo_conctryo_PSI', 'Cat_concreto_MT', 'Temperatura',
        'Hora_llegada', 'id_empelado', 'Observaciones', 'Estado',
        'Creado_por', 'Actualizado_por', 'Fecha_creacion', 'Fecha_actualizacion'
    ];

    public function empleado() {
        return $this->belongsTo(Empleado::class, 'id_empelado');
    }
}
