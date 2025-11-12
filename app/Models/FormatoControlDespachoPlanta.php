<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class FormatoControlDespachoPlanta extends Model
{
    use HasFactory;

    protected $table = 'tbl_formato_control_despacho_planta';
    protected $primaryKey = 'id_Formato_control_despacho_planta';
    public $timestamps = false;

    protected $fillable = [
        'Fecha',
        'No_envio',
        'id_Aldea',
        'Conductor',
        'Tipo_de_Concreto_ps',
        'Cantidad_Concreto_mT3',
        'Concreto_granel_kg',
        'Concreto_sacos_kg',
        'total',
        'kg_Piedrin',
        'kg_Arena',
        'Lts_Agua',
        'Aditivo1',
        'Aditivo2',
        'Aditivo3',
        'Aditivo4',
        'cantidad1',
        'cantidad2',
        'cantidad3',
        'cantidad4',
        'id_Empleados',
        'Supervisor',
        'Observaciones',
        'Estado',
        'Creado_por',
        'Actualizado_por',
        'Fecha_creacion',
        'Fecha_actualizacion'
    ];

    // Relación con aldea
    public function aldea()
    {
        return $this->belongsTo(Aldea::class, 'id_Aldea', 'id_aldea');
    }

    // Relación con empleado
    public function empleado()
    {
        return $this->belongsTo(Empleado::class, 'id_Empleados', 'id_Empleados');
    }

    // Auditoría automática
    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->Creado_por = Auth::id();
            $model->Fecha_creacion = now();
        });

        static::updating(function ($model) {
            $model->Actualizado_por = Auth::id();
            $model->Fecha_actualizacion = now();
        });
    }
}
