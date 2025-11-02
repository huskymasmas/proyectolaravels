<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetalleCarpeta extends Model
{
    protected $table = 'tbl_Detalle_Carpeta';
    protected $primaryKey = 'id_detalle_Carpeta';
    public $timestamps = false;

    protected $fillable = [
        'id_Proyecto', 'Valor', 'id_Unidades', 'Detalle', 'Calculo', 'Resultado',
        'Creado_por', 'Actualizado_por', 'Fecha_creacion', 'Fecha_actualizacion'
    ];

    public function unidad()
    {
        return $this->belongsTo(Unidad::class, 'id_Unidades', 'id_Unidades');
    }

    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class, 'id_Proyecto', 'id_Proyecto');
    }
}
