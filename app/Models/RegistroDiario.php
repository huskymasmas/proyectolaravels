<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class RegistroDiario extends Model
{
    use HasFactory;

    protected $table = 'tbl_Registro_diario';
    protected $primaryKey = 'id_Registro_diario';
    public $incrementing = true;
    public $timestamps = false;

    protected $fillable = [
        'id_Empleados',
        'id_Nomina',
        'fecha_dia',
        'dias_viaticos',
        'Adelanto_viatico',
        'Trabajo',
        'Horas_extras',
        'Adelantos',
        'Pago_Parcial',
        'Total_dia',
        'Estado',
        'Creado_por',
        'Actualizado_por',
        'Fecha_creacion',
        'Fecha_actualizacion',
    ];

    // RELACIONES
    public function empleado()
    {
        return $this->belongsTo(Empleado::class, 'id_Empleados', 'id_Empleados');
    }

    public function nomina()
    {
        return $this->belongsTo(Nomina::class, 'id_Nomina', 'id_Nomina');
    }

    // AUDITORÍA AUTOMÁTICA
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($registro) {
            $registro->Creado_por = Auth::id();
            $registro->Fecha_creacion = now();
            $registro->Estado = 1;
        });

        static::updating(function ($registro) {
            $registro->Actualizado_por = Auth::id();
            $registro->Fecha_actualizacion = now();
        });
    }
}
