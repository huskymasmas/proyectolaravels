<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ControlConcretoCampo extends Model
{
    use HasFactory;

    protected $table = 'tbl_control_concreto_campo';
    protected $primaryKey = 'id_control_concreto_campo';
    public $timestamps = false;

    protected $fillable = [
        'id_Aldea',
        'fecha',
        'codigo_envio_camion',
        'hora_llegada',
        'hora_inicio_vaciado',
        'hora_fin_vaciado',
        'volumen_m3',
        'asentamiento',
        'temperatura',
        'aire',
        'codigo_muestra',
        'cantidad_cilindros',
        'enviados_a',
        'fecha_envio',
        'resultado_psi_7d',
        'resultado_psi_14d',
        'resultado_psi_28d',
        'responsable',
        'Estado',
        'Creado_por',
        'Actualizado_por',
        'Fecha_creacion',
        'Fecha_actualizacion'
    ];

    public function aldea()
    {
        return $this->belongsTo(Aldea::class, 'id_Aldea', 'id_aldea');
    }
}
