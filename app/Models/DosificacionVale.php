<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DosificacionVale extends Model
{
    protected $table = 'tbl_Dosificacion_vale';
    protected $primaryKey = 'id_Dosificacion_vale';
    public $timestamps = false;

    protected $fillable = [
        'kg_cemento_granel','Sacos_Cemento','kg_piedirn','Kg_arena','lts_agua',
        'Estado','Creado_por','Actualizado_por','Fecha_creacion','Fecha_actualizacion'
    ];

    // Relación con ValeDespacho
    public function vale()
    {
        return $this->hasOne(\App\Models\ValeDespacho::class, 'id_Dosificacion_vale', 'id_Dosificacion_vale');
    }
}
