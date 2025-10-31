<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Unidad extends Model
{
    protected $table = 'tbl_Unidades';
    protected $primaryKey = 'id_Unidades';
    public $timestamps = false;

    protected $fillable = ['Nombre','Estado','Creado_por','Actualizado_por','Fecha_creacion','Fecha_actualizacion'];
}
