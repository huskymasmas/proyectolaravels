<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Origen extends Model
{
    protected $table = 'tbl_Origen';
    protected $primaryKey = 'id_Origen';
    public $timestamps = false;

    protected $fillable = ['Nombre','Estado','Creado_por','Actualizado_por','Fecha_creacion','Fecha_actualizacion'];
}
