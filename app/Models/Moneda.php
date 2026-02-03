<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Moneda extends Model
{
    protected $table = 'tbl_moneda';
    protected $primaryKey = 'id_moneda';
    public $timestamps = false; // Usamos fechas manuales

    protected $fillable = [
        'Nombre',
        'Estado',
        'Creado_por',
        'Actualizado_por',
        'Fecha_creacion',
        'Fecha_actualizacion',
    ];

    // === Eventos modelo para auditoría ===
    protected static function boot()
    {
        parent::boot();

        // Cuando crea un registro
        static::creating(function ($model) {
            $model->Creado_por = Auth::id();
            $model->Fecha_creacion = now()->format('Y-m-d');
        });

        // Cuando actualiza un registro
        static::updating(function ($model) {
            $model->Actualizado_por = Auth::id();
            $model->Fecha_actualizacion = now()->format('Y-m-d');
        });
    }
}
