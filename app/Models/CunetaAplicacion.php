<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CunetaAplicacion extends Model
{
    protected $table = 'cuneta_aplicacion';
    protected $primaryKey = 'id_cuneta';
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
