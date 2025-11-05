<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Departamento extends Model
{
    protected $table = 'tbl_Departamento';
    protected $primaryKey = 'id_Departamento';
    public $timestamps = false;

    protected $fillable = [
        'Nombres',
        'Creado_por',
        'Actualizado_por',
        'Fecha_creacion',
        'Fecha_actualizacion',
    ];


}
