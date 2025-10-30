<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Proyecto extends Model
{
    protected $table = 'tbl_Proyecto';
    protected $primaryKey = 'id_Proyecto';
    public $timestamps = false;
    protected $fillable = ['Nombre','id_Proyecto_detalle'];
}
