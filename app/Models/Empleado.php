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
    public function departamento()
{
    return $this->belongsTo(Departamento::class, 'id_Departamento', 'id_Departamento'); 
    // 'id' es la PK de tu tabla departamentos
   }

// Nueva relación con Rol
public function rol()
{
    return $this->belongsTo(Rol::class, 'id_Rol', 'id_Rol'); 
    // 'id_Rol' es la PK de tu tabla roles
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
