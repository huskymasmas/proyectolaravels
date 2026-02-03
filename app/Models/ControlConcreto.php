<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ControlConcreto extends Model
{
    use HasFactory;

    protected $table = 'tbl_control_concreto';
    protected $primaryKey = 'id_control';
    public $timestamps = false;

    protected $fillable = [
        'item_no', 'descripcion', 'unidad', 'cantidad_proyectada',
        'cantidad_colocada', 'observaciones', 'fecha_registro',
        'Estado', 'Creado_por', 'Actualizado_por', 'Fecha_creacion', 'Fecha_actualizacion'
    ];
}

