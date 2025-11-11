<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Empleado extends Model
{
    use HasFactory;

    protected $table = 'tbl_Empleados';
    protected $primaryKey = 'id_Empleados';
    public $incrementing = true;
    public $timestamps = false;

    protected $fillable = [
        'id_Departamento',
        'Nombres',
        'Apellido',
        'Apellido2',
        'Sexo',
        'Fecha_nacimiento',
        'Fecha_inicio',
        'DPI',
        'Numero',
        'id_Rol',
        'Estado',
        'Codigo_empleado',
        'Estado_trabajo',
        'Tipo_contrato',
        'id_Nomina',
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

    public function registrosDiarios()
    {
        return $this->hasMany(RegistroDiario::class, 'id_Empleados', 'id_Empleados');
    }

    // AUDITORÍA AUTOMÁTICA
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($empleado) {
            $empleado->Creado_por = Auth::id();
            $empleado->Fecha_creacion = now();
            $empleado->Estado = 1;
        });

        static::updating(function ($empleado) {
            $empleado->Actualizado_por = Auth::id();
            $empleado->Fecha_actualizacion = now();
        });
    }
}
