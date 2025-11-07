<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AditivoAplicados extends Model
{
    protected $table = 'tbl_Aditivo_aplicados';
    protected $primaryKey = 'id_Aditivo_aplicados';
    public $timestamps = false;

    protected $fillable = [
        'Nombre1','Nombre2','Nombre3','Nombre4',
        'Cantidad1','Cantidad2','Cantidad3','Cantidad4',
        'Firma1_ruta_imagen_encargado_palata','Firma2_ruta_imagen_coductor','Firma3_ruta_imagen_Resibi_conforme',
        'Nombre_encargado_palata','Nombre_coductor','Nombre_Resibi_conforme',
        'Estado','Creado_por','Actualizado_por','Fecha_creacion','Fecha_actualizacion'
    ];

    // Relación con ValeDespacho
    public function vale()
    {
        return $this->hasOne(\App\Models\ValeDespacho::class, 'id_Aditivo_aplicados', 'id_Aditivo_aplicados');
    }
}
