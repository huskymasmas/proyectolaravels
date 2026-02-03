<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ValeIngresoMaquinaria extends Model {
    protected $table = 'tbl_vale_ingreso_equipo_maquinaria_vehiculo';
    protected $primaryKey = 'id_vale_equipo_maquinaria_vehiculo';
    public $timestamps = false;
    protected $fillable = [
        'Nombre','Nombre_encargado','Nombre_Bodeguero','Nombre_Residente_obra',
        'marca','serie','placa','Fecha_ingreso','Hora_llegada',
        'empresa_proveedora','cantidad','estado_fisico','costo','id_moneda',
        'Num_factura','nit','Estado','Creado_por','Actualizado_por','Fecha_creacion','Fecha_actualizacion','id_Proyecto'
    ];
    public function moneda(){ 
        return $this->belongsTo(Moneda::class,'id_moneda','id_moneda'); 
    }
    public function proyecto()
    {
    return $this->belongsTo(Proyecto::class, 'id_Proyecto', 'id_Proyecto');
    }

    public function unidad() {
    return $this->belongsTo(Unidad::class, 'id_Unidades');
    }

    protected static function boot()
    {
        parent::boot();

        // Cuando crea un registro
        static::creating(function ($model) {
            $model->Creado_por = Auth::id();
            $model->Fecha_creacion = now()->format('Y-m-d');
        });

        // Cuando actualiza un registro
        static::updating(function ($model) {
            $model->Actualizado_por = Auth::id();
            $model->Fecha_actualizacion = now()->format('Y-m-d');
        });
    }
}
