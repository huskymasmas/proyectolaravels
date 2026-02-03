<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RodaduraAplicacion extends Model
{
    protected $table = 'rodadura_aplicacion';
    protected $primaryKey = 'id_rodadura';
    public $timestamps = false;

    protected $fillable = [
        'id_Ejes',
        'estacion_inicial',
        'estacion_final',
        'ancho',
        'rendimiento_m2',
        'Estado',
        'Creado_por',
        'Actualizado_por',
        'Fecha_creacion',
        'Fecha_actualizacion'
    ];

    public function eje()
    {
        return $this->belongsTo(Eje::class, 'id_Ejes', 'id_Ejes');
    }
}
