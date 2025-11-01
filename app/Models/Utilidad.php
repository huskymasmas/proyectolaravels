<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Utilidad extends Model
{
    protected $table = 'tbl_utilidades';
    protected $primaryKey = 'id_utilidades';
    public $timestamps = false;

    protected $fillable = [
        'Valor', 'id_Unidades', 'Detalle', 'Calculo', 'Resultado', 'Descripcion',
        'Estado', 'Creado_por', 'Actualizado_por', 'Fecha_creacion', 'Fecha_actualizacion'
    ];

    public function unidad()
    {
        return $this->belongsTo(Unidad::class, 'id_Unidades');
    }

    public function proyectos()
    {
        return $this->belongsToMany(Proyecto::class, 'tbl_Proyecto_Utilidades', 'id_utilidades', 'id_Proyecto');
    }
}
