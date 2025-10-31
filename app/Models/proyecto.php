<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Proyecto extends Model
{
    protected $table = 'tbl_Proyecto';
    protected $primaryKey = 'id_Proyecto';
    public $timestamps = false;

    protected $fillable = ['Nombre','id_Proyecto_detalle','Estado','Creado_por','Actualizado_por','Fecha_creacion','Fecha_actualizacion'];

    public function detalle(){ return $this->belongsTo(ProyectoDetalle::class, 'id_Proyecto_detalle'); }
    public function utilidades(){ return $this->hasMany(Utilidades::class, 'id_Proyecto'); }
}
