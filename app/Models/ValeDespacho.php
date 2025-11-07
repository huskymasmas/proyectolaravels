<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ValeDespacho extends Model
{
    protected $table = 'tbl_Vale_despacho';
    protected $primaryKey = 'id_Vale_despacho';
    public $timestamps = false;

    protected $fillable = [
        'No_vale','id_Despacho_concreto','id_Dosificacion_vale','id_Aditivo_aplicados',
        'Estado','Creado_por','Fecha_creacion'
    ];

    // Relaciones
    public function despacho()
    {
        return $this->belongsTo(DespachoConcreto::class, 'id_Despacho_concreto', 'id_Despacho_concreto');
    }

    public function dosificacion()
    {
        return $this->belongsTo(DosificacionVale::class, 'id_Dosificacion_vale', 'id_Dosificacion_vale');
    }

    public function aditivo()
    {
        return $this->belongsTo(AditivoAplicados::class, 'id_Aditivo_aplicados', 'id_Aditivo_aplicados');
    }
}
