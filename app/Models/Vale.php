<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Vale extends Model
{
    protected $table = 'tbl_Vale';
    protected $primaryKey = 'id_Vale';
    public $timestamps = false;
    protected $fillable = ['Codigo_planta','inicio_descarga','Finalizacion_descarga','Volumen_carga','Tipo_de_concreto','id_Carpeta'];
}
