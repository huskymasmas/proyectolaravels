<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProyectoUtilidad extends Model
{
    protected $table = 'tbl_Proyecto_Utilidades';
    protected $primaryKey = 'id_Proyecto_Utilidades';
    public $timestamps = false;

    protected $fillable = [
        'id_utilidades', 'id_Proyecto',
        'Creado_por', 'Actualizado_por', 'Fecha_creacion', 'Fecha_actualizacion'
    ];

    public function utilidad()
    {
        return $this->belongsTo(Utilidad::class, 'id_utilidades');
    }

    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class, 'id_Proyecto');
    }
}
