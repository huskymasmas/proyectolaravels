<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class produccion extends Model
{
    protected $table = 'tbl_Produccion';
    protected $primaryKey = 'id_Produccion';
    public $timestamps = false; 

    protected $fillable = [
        'id_Proyecto','id_Dosificacion','id_Planta','Fecha',
        'Volumen_m3','Cemento_kg','Arena_m3','Piedrin_m3','Aditivo_l'
    ];

    public function dosificacion(){ return $this->belongsTo(Dosificacion::class, 'id_Dosificacion'); }
    public function planta(){ return $this->belongsTo(Planta::class, 'id_Planta'); }
    public function proyecto(){ return $this->belongsTo(Proyecto::class, 'id_Proyecto'); }
}
