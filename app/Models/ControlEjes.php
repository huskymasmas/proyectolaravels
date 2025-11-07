<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ControlEjes extends Model
{
    use HasFactory;

    protected $table = 'tbl_Control_ejes';
    protected $primaryKey = 'id_Rodadura_Control';
    public $timestamps = false;

    protected $fillable = [
        'tipo', 'eje', 'Estacion_inicial', 'Estacion_final', 'Ancho_prom',
        'M2', 'Rend_m3', 'Estado', 'Creado_por', 'Actualizado_por',
        'Fecha_creacion', 'Fecha_actualizacion'
    ];
}
