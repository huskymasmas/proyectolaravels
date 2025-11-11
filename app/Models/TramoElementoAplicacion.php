<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TramoElementoAplicacion extends Model
{
    protected $table = 'tramo_elemento_aplicacion';
    public $timestamps = false;
    public $incrementing = false; // porque no hay PK autoincremental

    protected $fillable = [
        'id_tramo',
        'id_rodadura',
        'id_cuneta'
    ];

    public function tramo()
    {
        return $this->belongsTo(TramoAplicacion::class, 'id_tramo', 'id_tramo');
    }

    public function rodadura()
    {
        return $this->belongsTo(RodaduraAplicacion::class, 'id_rodadura', 'id_rodadura');
    }

    public function cuneta()
    {
        return $this->belongsTo(CunetaAplicacion::class, 'id_cuneta', 'id_cuneta');
    }
}
