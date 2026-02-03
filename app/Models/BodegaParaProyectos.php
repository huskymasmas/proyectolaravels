<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class BodegaParaProyectos extends Model
{
    protected $table = 'tbl_bodega_para_proyectos';
    protected $primaryKey = 'id_Bodega_para_proyectos';
    public $timestamps = false;

    protected $fillable = [
        'id_Proyecto',
        'Material',
        'id_Unidades',
        'Cantidad_maxima',
        'Usado',
        'Almazenado',
        'Estado',
        'Creado_por',
        'Actualizado_por',
        'Fecha_creacion',
        'Fecha_actualizacion'
    ];
     public function proyecto()
    {
        return $this->belongsTo(Proyecto::class, 'id_Proyecto');
    }
     public function unidades()
    {
        return $this->belongsTo(Unidad::class, 'id_Unidades');
    }

    // AUDITORÍA AUTOMÁTICA
    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->Creado_por = Auth::id();
            $model->Fecha_creacion = now();
            $model->Estado = 1;
        });

        static::updating(function ($model) {
            $model->Actualizado_por = Auth::id();
            $model->Fecha_actualizacion = now();

            $model->Estado = 1;

        });
    }
}
