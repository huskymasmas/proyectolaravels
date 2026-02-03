<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ValeEgresoMaterialesVarios extends Model
{
    protected $table = 'tbl_vale_egreso_materiales_varios';
    protected $primaryKey = 'id_vale_egreso_Materiales_varios';
    public $timestamps = false;

    protected $fillable = [
        'Nombre',
        'id_Unidades',
        'Nombre_encargado',
        'Nombre_Bodeguero',
        'Nombre_Residente_obra',
        'Nombre_conductor',
        'marca',
        'serie',
        'placa',
        'Fecha',
        'Hora_llegada',
        'Inicio_carga',
        'Finalizacion_carga',
        'Hora_salida_planta',
        'cantidad',
        'observaciones',
        'Estado',
        'Creado_por',
        'Actualizado_por',
        'Fecha_creacion',
        'Fecha_actualizacion'
    ];

        // Relación con Unidad
    public function unidad()
    {
        return $this->belongsTo(Unidad::class, 'id_Unidades');
    }
        public function proyecto()
    {
        return $this->belongsTo(Proyecto::class, 'id_Proyecto');
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
        });
    }
}
