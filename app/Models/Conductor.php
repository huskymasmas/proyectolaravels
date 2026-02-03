<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Conductor extends Model
{
    protected $table = 'tbl_Conductor';
    protected $primaryKey = 'id_Conductor';
    public $timestamps = false;

    protected $fillable = [
        'Nombre','Apellido','Nacimeiento','Estado','Creado_por','Actualizado_por','Fecha_creacion','Fecha_actualizacion'
    ];

    public function carpetas(){ return $this->hasMany(Carpeta::class, 'id_Conductor'); }
}
