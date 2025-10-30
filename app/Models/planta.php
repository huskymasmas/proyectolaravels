<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Planta extends Model
{
    protected $table = 'tbl_Planta';
    protected $primaryKey = 'id_Planta';
    public $timestamps = false;
    protected $fillable = ['Nombre','Ubicacion','Encargado'];
}
