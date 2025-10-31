<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class RequerimientoCuetas extends Model
{
    protected $table = 'tbl_Requerimiento_cuetas';
    protected $primaryKey = 'id_Requerimiento_cuetas';
    public $timestamps = false;

    protected $fillable = [
        'id_Materiales','Cantidad','Estado','Creado_por','Actualizado_por','Fecha_creacion','Fecha_actualizacion'
    ];

    public function material(){ return $this->belongsTo(Material::class, 'id_Materiales'); }
}
