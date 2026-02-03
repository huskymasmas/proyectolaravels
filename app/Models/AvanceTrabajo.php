<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AvanceTrabajo extends Model
{
    protected $table = "tbl_avances_trabajo";
    protected $primaryKey = "id_avances_trabajo";
    public $timestamps = false;

    protected $fillable = [
        'id_aldea',
        'id_trabajos',
        'Cantidad',
        'Estado',
        'Creado_por',
        'Actualizado_por',
        'Fecha_creacion',
        'Fecha_actualizacion'
    ];

    protected $casts = [
        'Fecha_creacion' => 'date',
        'Fecha_actualizacion' => 'date',
    ];

    public function aldea()
    {
        return $this->belongsTo(Aldea::class, 'id_aldea');
    }

  public function trabajo()
{
    return $this->belongsTo(Trabajo::class, 'id_trabajos', 'id_trabajos');
}

}
