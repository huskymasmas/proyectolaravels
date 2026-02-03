<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class MaquinaUso extends Model {
    protected $table = 'tbl_maquina_uso';
    protected $primaryKey = 'id_maquina_uso';
    public $timestamps = false;
    protected $fillable = [
        'maquina','cantidad','proyecto','Fecha','Fecha_desuso','Estado','Creado_por','Actualizado_por','Fecha_creacion','Fecha_actualizacion',
        'origen','origen_id'
    ];
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
