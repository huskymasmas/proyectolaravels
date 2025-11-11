<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TramoAplicacion extends Model
{
    protected $table = 'tramo_aplicacion';
    protected $primaryKey = 'id_tramo';
    public $timestamps = false;

    protected $fillable = [
        'id_Proyecto',
        'fecha',
        'aplicador',
        'cubeta_bomba',
        'supervisor',
        'observaciones',
        'Aditivo_Ancho',
        'Rendimiento_M2',
        'Estado',
        'Creado_por',
        'Actualizado_por',
        'Fecha_creacion',
        'Fecha_actualizacion'
    ];

    // Relaciones
    public function rodaduras()
    {
        return $this->hasManyThrough(
            RodaduraAplicacion::class,
            TramoElementoAplicacion::class,
            'id_tramo', // Foreign key on pivot table
            'id_rodadura', // Foreign key on rodadura table
            'id_tramo', // Local key on this table
            'id_rodadura' // Local key on pivot table
        );
    }

    public function cunetas()
    {
        return $this->hasManyThrough(
            CunetaAplicacion::class,
            TramoElementoAplicacion::class,
            'id_tramo',
            'id_cuneta',
            'id_tramo',
            'id_cuneta'
        );
    }

    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class, 'id_Proyecto', 'id_Proyecto');
    }
}
