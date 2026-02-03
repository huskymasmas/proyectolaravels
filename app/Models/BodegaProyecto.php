<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BodegaProyecto extends Model
{
    protected $table = 'tbl_bodega_proyecto';
    protected $primaryKey = 'id_Bodega_proyecto';
    public $timestamps = false;

    protected $fillable = [
        'id_Proyecto',
        'No_vale',
        'Fecha',
        'Material',
        'id_Unidades',
        'Cantidad',
        'Equivalen',
        'Equivalencia_M3',
        'Conductor',
        'Placa_vehiculo',
        'Origen'
    ];

    // 🔗 Relaciones
    public function unidad()
    {
        return $this->belongsTo(Unidad::class, 'id_Unidades', 'id_Unidades');
    }

    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class, 'id_Proyecto', 'id_Proyecto');
    }
}
