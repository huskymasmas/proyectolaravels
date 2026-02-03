<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DespachoConcreto extends Model
{
    protected $table = 'tbl_despacho_concreto';
    protected $primaryKey = 'id_Despacho_concreto';
    public $timestamps = false;

    protected $fillable = [
        'Codigo_planta','id_Proyecto','Fecha','Volumen_carga_M3',
        'Hora_salida_plata','Tipo_Concreto','id_Empresa','Inicio_Carga',
        'Finaliza_carga','Hora_llega_Proyecto','Tipo_elemento',
        'Placa_numero','Estado','Creado_por','Actualizado_por',
        'Fecha_creacion','Fecha_actualizacion'
    ];

    // Relación con Proyecto
    public function proyecto()
    {
        return $this->belongsTo(\App\Models\Proyecto::class, 'id_Proyecto', 'id_Proyecto');
    }

    // Relación con Empresa
    public function empresa()
    {
        return $this->belongsTo(\App\Models\Empresa::class, 'id_Empresa', 'id_Empresa');
    }

    // Relación con ValeDespacho
    public function vales()
    {
        return $this->hasMany(\App\Models\ValeDespacho::class, 'id_Despacho_concreto', 'id_Despacho_concreto');
    }
}
