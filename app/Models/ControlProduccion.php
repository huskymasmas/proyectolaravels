<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ControlProduccion extends Model
{
    use HasFactory;

    protected $table = 'tbl_control_produccion';
    protected $primaryKey = 'id_control_produccion';

    protected $fillable = [
        'id_Tipo_dosificacion',
        'id_Proyecto',
        'fecha',
        'cemento_sacos',
        'Cemento_total',
        'Arena_kg',
        'Piedrin_kg',
        'Aditivo',
        'Piedrin_salida',
        'Arena_salida',
        'Coductor',
        'Placa',
        'viajes',
        'Creado_por',
        'Actualizado_por',
        'Fecha_creacion',
        'Fecha_actualizacion',
       
    ];

    // NO usaremos timestamps de Laravel porque ya usamos columnas propias
    public $timestamps = false;

    protected static function boot()
    {
        parent::boot();

        // ✔ Evento al crear
        static::creating(function ($model) {
            if (Auth::id()) {
                $model->Creado_por = Auth::id();
            }
            $model->Fecha_creacion = now();
        });

        // ✔ Evento al actualizar
        static::updating(function ($model) {
            if (Auth::id()) {
                $model->Actualizado_por = Auth::id();
            }
            $model->Fecha_actualizacion = now();
        });
    }

    // ✔ Relaciones
    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class, 'id_Proyecto');
    }

    public function dosificacion()
    {
        return $this->belongsTo(Tipo_dosificador::class, 'id_Tipo_dosificacion');
    }
}
