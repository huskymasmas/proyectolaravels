<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Configuracion extends Model
{
    protected $table = 'tbl_Configuracion';
    protected $primaryKey = 'id_Configuracion';
    public $timestamps = false;

    protected $fillable = [
        'Parametros','Valor','NOTAS','Creado_por','Actualizado_por','Fecha_creacion','Fecha_actualizacion'
    ];

    public function creador(){ return $this->belongsTo(Usuario::class, 'Creado_por'); }
}
