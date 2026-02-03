<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Plano extends Model
{
    protected $table = 'tbl_planos';
    protected $primaryKey = 'id_planos';
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'datos',
        'id_aldea',
        'id_trabajo',
        'Creado_por',
        'Actualizado_por',
        'Fecha_creacion',
        'Fecha_actualizacion'
    ];

    public function trabajo()
    {
        return $this->belongsTo(Trabajo::class, 'id_trabajo');
    }
     /** Metadatos automáticos */
    protected static function boot()
    {
        parent::boot();

        // Al crear
        static::creating(function ($plano) {
            $plano->Creado_por = Auth::id();
            $plano->Fecha_creacion = now();
        });

        // Al actualizar
        static::updating(function ($plano) {
            $plano->Actualizado_por = Auth::id();
            $plano->Fecha_actualizacion = now();
        });
    }
}
