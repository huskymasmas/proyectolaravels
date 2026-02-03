<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ValeIngreso extends Model
{
    use HasFactory;

    protected $table = 'tbl_Vale_ingreso';
    protected $primaryKey = 'id_Vale_ingreso';
    public $timestamps = false;

    protected $fillable = [
        'Fecha',
        'Hora_llegada',
        'Tipo_material',
        'Cantidad',
        'id_Unidades',
        'id_Proyecto',
        'id_Empresa',
        'Observaciones',
        'Nombre_coductor',
        'Nombre_encargado_palata',
        'Nombre_bodegero',
        'Nombre_residente_obra',
        'Placa_vehiculo',
        'Origen_material',
        'Num_factura',
        'nit',
        'precio_unitario',
        'precio_total',
        'Firma1_ruta_imagen_encargado_palata',
        'Firma2_ruta_imagen_bodegero',
        'Firma3_ruta_imagen_residente_obra',
        'Estado',
        'Creado_por',
        'Actualizado_por',
        'Fecha_creacion',
        'Fecha_actualizacion',
    ];

    public function unidad() {
        return $this->belongsTo(Unidad::class, 'id_Unidades');
    }
     public function proyecto()
    {
    return $this->belongsTo(Proyecto::class, 'id_Proyecto', 'id_Proyecto');
    }
}
