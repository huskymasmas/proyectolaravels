<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Carpeta extends Model
{
    protected $table = 'tbl_Carpeta';
    protected $primaryKey = 'id_Carpeta';
    public $timestamps = false;

    protected $fillable = [
        'Fecha','VALE','M3_enviados','Cemento_KG','Cemento_sacos','Cemento_total',
        'Arena_salida_KG','Piedri_salida_KG','Aditivo_EN','Arena_Salida','Viajes',
        'id_Conductor','Placa','Estado','Creado_por','Actualizado_por','Fecha_creacion','Fecha_actualizacion'
    ];

    public function conductor(){ return $this->belongsTo(Conductor::class, 'id_Conductor'); }
    public function vales(){ return $this->hasMany(Vale::class, 'id_Carpeta'); }
}
