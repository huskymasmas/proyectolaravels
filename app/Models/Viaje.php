<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Viaje extends Model
{
    protected $table = 'tbl_Viaje';
    protected $primaryKey = 'id_Viaje';
    public $timestamps = false;

    protected $fillable = [
        'id_Conductor','Placa','ConsumoGasolina','Estado',
        'Creado_por','Actualizado_por'
    ];

    public function conductor(){ return $this->belongsTo(Conductor::class, 'id_Conductor'); }
    public function bodegas(){ return $this->hasMany(Bodega::class, 'id_Viaje'); }
    public function carpetas(){ return $this->hasMany(Carpeta::class, 'id_Viaje'); }
}
