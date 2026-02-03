<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class EstacionBodega extends Model
{
    protected $table = 'tbl_estacion_bodega';
    protected $primaryKey = 'id_estacion_Bodega';
    public $timestamps = false;

    protected $fillable = [
        'material',
        'cantidad',
        'id_Unidades',
        'proyecto',
        'Estado',
        'Creado_por',
        'Actualizado_por',
        'Fecha_creacion',
        'Fecha_actualizacion'
    ];

        // Relación con la tabla tbl_Unidades
    public function unidad()
    {
        return $this->belongsTo(Unidad::class, 'id_Unidades', 'id_Unidades');
    }


    // AUDITORÍA AUTOMÁTICA
    public static function boot()
    {
        parent::boot();

        // Al crear
        static::creating(function ($model) {
            $model->Creado_por = Auth::id();
            $model->Fecha_creacion = now();
        });

        // Al actualizar
        static::updating(function ($model) {
            $model->Actualizado_por = Auth::id();
            $model->Fecha_actualizacion = now();
        });
    }
}
