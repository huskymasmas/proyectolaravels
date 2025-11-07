<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ControlConcretoDetalle extends Model
{
    use HasFactory;

    protected $table = 'tbl_Control_concreto';
    protected $primaryKey = 'id_Control_concreto';
    public $timestamps = false;

    protected $fillable = [
        'id_Proyecto', 'fecha', 'Codigo_envio_Camion', 'tiempo_llegada',
        'Inici_vaciado', 'termina_vaciado', 'vol_m3', 'Asent', 'Temp', 'AIre',
        'Cilidros_No_cod_muestra', 'cantidad_cilindro', 'Enviado_A', 'fecha_envio',
        'ResultadosPSI7D', 'ResultadosPSI14D', 'ResultadosPSI28D', 'id_Empleados',
        'Estado', 'Creado_por', 'Actualizado_por', 'Fecha_creacion', 'Fecha_actualizacion'
    ];

    public function empleado() {
        return $this->belongsTo(Empleado::class, 'id_Empleados');
    }

    public function proyecto() {
        return $this->belongsTo(Proyecto::class, 'id_Proyecto');
    }
}
