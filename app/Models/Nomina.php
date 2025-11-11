<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Nomina extends Model
{
    use HasFactory;

    protected $table = 'tbl_nomina';
    protected $primaryKey = 'id_Nomina';
    public $timestamps = false;

    protected $fillable = [
        'sueldo_Base',
        'Costo_horas_extras',
        'Bonos',
        'Bonos_adicional',
        'viaticosnomina',
        'Estado',
        'Fecha_creacion',
        'Fecha_actualizacion',
        'Creado_por',
        'Actualizado_por'
    ];

    // 🔹 Campos de auditoría automáticos
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->Creado_por = Auth::id();
            $model->Fecha_creacion = now();
        });

        static::updating(function ($model) {
            $model->Actualizado_por = Auth::id();
            $model->Fecha_actualizacion = now();
        });
    }
}
