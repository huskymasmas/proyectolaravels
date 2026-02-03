<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstadoTrabajo extends Model
{
    use HasFactory;

    protected $table = 'tbl_estado_trabajo';
    protected $primaryKey = 'id_Estado_trabajo';
    public $timestamps = false;

    protected $fillable = [
        'Nombre',
        'Estado',
        'Creado_por',
        'Actualizado_por',
        'Fecha_creacion',
        'Fecha_actualizacion'
    ];
}
