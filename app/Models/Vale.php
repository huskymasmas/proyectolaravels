<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Vale extends Model
{
    protected $table = 'tbl_Vale';
    protected $primaryKey = 'id_Vale';
    public $timestamps = false;

    protected $fillable = [
        'Numero_vale','M3_enviados','Tipo_concreto','Inicio_descarga','Finalizacion_descarga',
        'Volumen_Carga','id_Carpeta'
    ];

    public function carpeta(){ return $this->belongsTo(Carpeta::class, 'id_Carpeta'); }
}
