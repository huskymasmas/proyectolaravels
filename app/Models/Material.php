<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    protected $table = 'tbl_Materiales';
    protected $primaryKey = 'id_Materiales';
    public $timestamps = false;

    protected $fillable = [
        'Nombre','Estado','Creado_por','Actualizado_por','Fecha_creacion','Fecha_actualizacion'
    ];

    public function creador(){ return $this->belongsTo(Usuario::class, 'Creado_por'); }
}
