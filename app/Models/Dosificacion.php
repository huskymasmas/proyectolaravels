<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Dosificacion extends Model
{
    protected $table = 'tbl_Dosificacion';
    protected $primaryKey = 'id_Dosificacion';
    public $timestamps = false;

    protected $fillable = [
        'id_Proyecto','id_Tipo_dosificacion','Cemento','Arena','Pedrin','Aditivo','Nota','Estado',
        'Creado_por','Actualizado_por','Fecha_creacion','Fecha_actualizacion'
    ];

     public function Tipo_dosificador()
    {
        return $this->belongsTo(Tipo_dosificador::class, 'id_Tipo_dosificacion');
    }
       public function proyecto()
    {
        return $this->belongsTo(Proyecto::class, 'id_Proyecto');
    }
}
