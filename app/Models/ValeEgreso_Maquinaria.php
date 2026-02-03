<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ValeEgreso_Maquinaria extends Model
{
    protected $table = 'tbl_vale_egreso_equipo_maquinaria_vehiculo';
    protected $primaryKey = 'id_vale_egreso_equipo_maquinaria_vehiculo';
    public $timestamps = false;

    protected $fillable = [
        'Nombre',
        'cantidad',
        'id_Proyecto',
        'Nombre_encargado',
        'Nombre_Recibe_conforme',
        'Nombre_Conductor',
        'Nombre_Bodeguero',
        'Fecha',
        'Hora_salida',
        'Hora_llegada',
        'marca',
        'serie',
        'placa',
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
        });
    }
}
