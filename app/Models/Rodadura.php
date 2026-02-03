<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rodadura extends Model
{
    protected $table = 'rodadura';
    protected $primaryKey = 'id_rodadura';
    public $timestamps = false;

    protected $fillable = [
        'id_Ejes',
        'estacion_inicial',
        'estacion_final',
        'ancho_prom',
        'm2',
        'rendimiento_m3',
        'Estado',
        'Creado_por',
        'Actualizado_por',
        'Fecha_creacion',
        'Fecha_actualizacion',
    ];

    public function eje()
    {
        return $this->belongsTo(Eje::class, 'id_Ejes');
    }
      // 🔹 Relación muchos a muchos con Tramo
    public function tramos()
    {
        return $this->belongsToMany(
            Tramo::class,
            'tramo_elemento',
            'id_rodadura',
            'id_tramo'
        )->withPivot([
            'Estado',
            'Creado_por',
            'Actualizado_por',
            'Fecha_creacion',
            'Fecha_actualizacion'
        ]);
    }
}
