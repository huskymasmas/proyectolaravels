<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Dosificacion extends Model
{
    protected $table = 'tbl_Dosificacion';
    protected $primaryKey = 'id_Dosificacion';
    public $timestamps = false;

    protected $fillable = [
        'Tipo','Cemento','Arena','Pedrin','Aditivo','Nota','Estado',
        'Creado_por','Actualizado_por','Fecha_creacion','Fecha_actualizacion'
    ];

    public function creador(){ return $this->belongsTo(Usuario::class, 'Creado_por'); }
}
