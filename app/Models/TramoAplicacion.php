<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class TramoAplicacion extends Model
{
    use HasFactory;

    protected $table = 'tramo_aplicacion';
    protected $primaryKey = 'id_tramo';
    public $timestamps = false;

    protected $fillable = [
        'id_aldea', 'fecha', 'aplicador', 'cubeta_bomba', 'supervisor',
        'observaciones', 'Aditivo_Ancho', 'Rendimiento_M2',
        'Estado', 'Creado_por', 'Actualizado_por', 'Fecha_creacion', 'Fecha_actualizacion',
    ];

    public function aldea()
    {
        return $this->belongsTo(Aldea::class, 'id_aldea', 'id_aldea');
    }

    public function rodaduras()
    {
        // Relación directa a TramoElementoAplicacion y RodaduraAplicacion
        return $this->hasManyThrough(
            RodaduraAplicacion::class,
            TramoElementoAplicacion::class,
            'id_tramo', // FK en tramo_elemento_aplicacion
            'id_rodadura', // PK en rodadura_aplicacion
            'id_tramo', // PK en tramo_aplicacion
            'id_rodadura' // FK en tramo_elemento_aplicacion
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
}
