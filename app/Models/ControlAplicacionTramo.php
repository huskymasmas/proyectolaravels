<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ControlAplicacionTramo extends Model
{
    use HasFactory;

    protected $table = 'tbl_Control_aplicacion_tramo';
    protected $primaryKey = 'id_Control_aplicacion_tramo';
    public $timestamps = false;

    protected $fillable = [
        'Fecha', 'Aplicador', 'Cubeta_bomba', 'Cat_concreto_MT', 'Ancho',
        'Rendimiento_M2', 'id_empelado', 'Observaciones', 'Estado',
        'Creado_por', 'Actualizado_por', 'Fecha_creacion', 'Fecha_actualizacion'
    ];

    public function empleado() {
        return $this->belongsTo(Empleado::class, 'id_empelado');
    }
}
