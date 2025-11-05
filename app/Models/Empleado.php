<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    protected $table = 'tbl_Empleados';
    protected $primaryKey = 'id_Empleados';
    public $timestamps = false;

    protected $fillable = [
        'id_Departamento', 'Nombres', 'Apellido', 'Apellido2', 'Sexo',
        'Fecha_nacimiento', 'Fecha_inicio', 'DPI', 'Numero',
        'id_Nomina', 'id_Rol', 'Estado',
        'Creado_por', 'Actualizado_por', 'Fecha_creacion', 'Fecha_actualizacion'
    ];

    public function rol()
    {
        return $this->belongsTo(Rol::class, 'id_Rol');
    }

    public function departamento()
    {
        return $this->belongsTo(Departamento::class, 'id_Departamento');
    }

    public function nomina()
    {
        return $this->belongsTo(Nomina::class, 'id_Nomina');
    }

    public function asistencias()
    {
        return $this->hasMany(Asistencia::class, 'id_Empleados');
    }
}
