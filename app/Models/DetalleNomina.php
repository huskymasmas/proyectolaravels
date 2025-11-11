<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class DetalleNomina extends Model
{
    use HasFactory;

    protected $table = 'tbl_detalle_nomina';
    protected $primaryKey = 'id_detalle_nomina';
    public $incrementing = true;
    public $timestamps = false;

    protected $fillable = [
        'id_Nomina',
        'Horas_extras',
        'cantidad_dias',
        'totla_A_pagar',
        'Estado',
        'Creado_por',
        'Actualizado_por',
        'Fecha_creacion',
        'Fecha_actualizacion',
    ];

    // RELACIONES
    public function nomina()
    {
        return $this->belongsTo(Nomina::class, 'id_Nomina', 'id_Nomina');
    }

    // AUDITORÍA AUTOMÁTICA
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($detalle) {
            $detalle->Creado_por = Auth::id();
            $detalle->Fecha_creacion = now();
            $detalle->Estado = 1;
        });

        static::updating(function ($detalle) {
            $detalle->Actualizado_por = Auth::id();
            $detalle->Fecha_actualizacion = now();
        });
    }
}
