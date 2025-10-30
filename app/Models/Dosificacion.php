<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Dosificacion extends Model
{
    protected $table = 'tbl_Dosificacion';
    protected $primaryKey = 'id_Dosificacion';
    public $timestamps = false;
    protected $fillable = ['Tipo','Cemento','Arena','Pedrin','Aditivo','Nota'];
}
