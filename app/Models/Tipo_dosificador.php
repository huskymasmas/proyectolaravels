<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Tipo_dosificador extends Model
{
    protected $table = 'tbl_tipo_dosificacion';
    protected $primaryKey = 'id_Tipo_dosificacion';
    public $timestamps = false;

    protected $fillable = [
        'Nombre'
    ];

    
}
