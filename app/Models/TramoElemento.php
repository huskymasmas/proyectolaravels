<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TramoElemento extends Model
{
    protected $table = 'tramo_elemento';
    public $timestamps = false;

    protected $fillable = [
        'id_tramo',
        'id_rodadura',
        'id_cuneta',
        'Estado',
        'Creado_por',
        'Actualizado_por',
        'Fecha_creacion',
        'Fecha_actualizacion',
    ];

    public function tramo()
    {
        return $this->belongsTo(Tramo::class, 'id_tramo');
    }

    public function rodadura()
    {
        return $this->belongsTo(Rodadura::class, 'id_rodadura');
    }

    public function cuneta()
    {
        return $this->belongsTo(Cuneta::class, 'id_cuneta');
    }
}
