<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProyectoRequerimientoCarpetaRodadura extends Model
{
    protected $table = 'tbl_Proyecto_Requerimiento_carrpeta_rodadura';
    protected $primaryKey = 'id_Proyecto_Requerimiento_carrpeta_rodadura';
    public $timestamps = false;

    protected $fillable = [
        'id_Requerimiento_carrpeta_rodadura', 'id_Proyecto',
        'Creado_por', 'Actualizado_por', 'Fecha_creacion', 'Fecha_actualizacion'
    ];

    public function requerimiento()
    {
        return $this->belongsTo(RequerimientoCarpetaRodadura::class, 'id_Requerimiento_carrpeta_rodadura');
    }

    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class, 'id_Proyecto');
    }
}
