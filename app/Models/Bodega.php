<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Bodega extends Model
{
    protected $table = 'tbl_Bodega';
    protected $primaryKey = 'id_Bodega';
    public $timestamps = false;

    protected $fillable = [
        'Fecha','id_Materiales','id_Unidades','Cantidad','Equivalent','Equivalen_M3',
        'id_Conductor','Placa','id_Empresa','id_Origen','Estado','Creado_por','Actualizado_por','Fecha_creacion','Fecha_actualizacion'
    ];

    public function material(){ return $this->belongsTo(Material::class, 'id_Materiales'); }
    public function unidad(){ return $this->belongsTo(Unidad::class, 'id_Unidades'); }
    public function conductor(){ return $this->belongsTo(Conductor::class, 'id_Conductor'); }
    public function empresa(){ return $this->belongsTo(Empresa::class, 'id_Empresa'); }
    public function origen(){ return $this->belongsTo(Origen::class, 'id_Origen'); }
}
