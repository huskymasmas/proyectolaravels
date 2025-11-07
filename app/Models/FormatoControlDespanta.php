<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormatoControlDespanta extends Model
{
    use HasFactory;

    protected $table = 'tbl_Fromato_control_de_Despanta';
    protected $primaryKey = 'id_Fromato_control_de_Despanta';
    public $timestamps = false;

    protected $fillable = [
        'No_envio', 'Coductor', 'Tipo_Cencreto', 'Cantidad_concreto_MT3',
        'kg_Granel', 'Kg_Sacos', 'Total', 'kg_Piedrín', 'kg_Arena', 'Lts_Agua',
        'Aditivo_cant_lts1', 'Aditivo_cant_lts2', 'Aditivo_cant_lts3',
        'Nombre1', 'Nombre2', 'Nombre3', 'id_empleado', 'Observaciones',
        'Estado', 'Creado_por', 'Actualizado_por', 'Fecha_creacion', 'Fecha_actualizacion'
    ];

    public function empleado() {
        return $this->belongsTo(Empleado::class, 'id_empleado');
    }
}
