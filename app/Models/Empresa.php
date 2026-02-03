<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    protected $table = 'tbl_empresa';
    protected $primaryKey = 'id_Empresa';
    public $timestamps = false;

    protected $fillable = ['Nombre','Estado','Creado_por','Actualizado_por','Fecha_creacion','Fecha_actualizacion'];
}
