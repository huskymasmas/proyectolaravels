<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ProyectoDetalle extends Model
{
    protected $table = 'tbl_proyecto_detalle';
    protected $primaryKey = 'id_Proyecto_detalle';
    public $timestamps = false;

    protected $fillable = ['Descripcion','Ubicacion','Estado','Creado_por','Actualizado_por','Fecha_creacion','Fecha_actualizacion'];
}
