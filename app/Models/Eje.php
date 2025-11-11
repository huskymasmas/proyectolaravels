<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Eje extends Model
{
    protected $table = 'tbl_Ejes'; 
    protected $primaryKey = 'id_Ejes';
    public $timestamps = false;

    protected $fillable = [
        'nombre_eje',
        'descripcion',
        'Estado',
        'Creado_por',
        'Actualizado_por',
        'Fecha_creacion',
        'Fecha_actualizacion',
    ];

    // 🔗 Relación con Rodadura
    public function rodaduras()
    {
        return $this->hasMany(Rodadura::class, 'id_Ejes');
    }

    // 🔗 Relación con Cuneta
    public function cunetas()
    {
        return $this->hasMany(Cuneta::class, 'id_Ejes');
    }
}
