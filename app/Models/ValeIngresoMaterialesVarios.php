<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ValeIngresoMaterialesVarios extends Model
{
    protected $table = 'tbl_vale_ingreso_materiales_varios';
    protected $primaryKey = 'id_vale_ingreso_materiales_varios';
    public $timestamps = false;

    protected $fillable = [
        'Nombre',
        'id_Unidades',
        'id_Proyecto',
        'Nombre_encargado',
        'Nombre_Bodeguero',
        'Nombre_Residente_obra',
        'Nombre_conductor',
        'marca',
        'serie',
        'placa',
        'Fecha_ingreso',
        'Hora_llegada',
        'empresa_proveedora',
        'cantidad',
        'Total_pagar',
        'estado_fisico',
        'costo',
        'id_moneda',
        'Num_factura',
        'nit',
        'Estado',
        'Creado_por',
        'Actualizado_por',
        'Fecha_creacion',
        'Fecha_actualizacion'
    ];

    // Relaciones
    public function unidad()
    {
        return $this->belongsTo(Unidad::class, 'id_Unidades');
    }

    public function moneda()
    {
        return $this->belongsTo(Moneda::class, 'id_moneda');
    }
    public function proyecto()
    {
    return $this->belongsTo(Proyecto::class, 'id_Proyecto', 'id_Proyecto');
    }


    // Auditoría automática
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
