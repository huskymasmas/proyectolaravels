<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
    protected $table = 'tbl_rol';
    protected $primaryKey = 'id_Rol';
    public $timestamps = false;

    protected $fillable = [
        'Nombre', 'Estado',
        'Creado_por', 'Actualizado_por',
        'Fecha_creacion', 'Fecha_actualizacion'
    ];

    public function empleados()
    {
        return $this->hasMany(Empleado::class, 'id_Rol');
    }
}
