<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class DetalleObra extends Model
{
    use HasFactory;

    protected $table = 'tbl_Detalle_obra';
    protected $primaryKey = 'id_Detalle_obra';
    public $timestamps = false;

    protected $fillable = [
        'id_Proyecto',
        'Tipo_Obra',
        'Valor',
        'id_Unidades',
        'Detalle',
        'Calculo',
        'Resultado',
        'Descripcion',
        'Estado',
        'Creado_por',
        'Actualizado_por',
        'Fecha_creacion',
        'Fecha_actualizacion'
    ];

    // Relaciones
    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class, 'id_Proyecto', 'id_Proyecto');
    }

    public function unidad()
    {
        return $this->belongsTo(Unidad::class, 'id_Unidades', 'id_Unidades');
    }

    // Eventos para auditoría
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($detalleObra) {
            $detalleObra->Creado_por = Auth::id();
            $detalleObra->Fecha_creacion = now();
        });

        static::updating(function ($detalleObra) {
            $detalleObra->Actualizado_por = Auth::id();
            $detalleObra->Fecha_actualizacion = now();
        });
    }
}
