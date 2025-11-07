<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trabajo extends Model
{
    use HasFactory;

    protected $table = 'tbl_trabajos';
    protected $primaryKey = 'id_trabajos';
    public $timestamps = false;

    protected $fillable = [
        'id_Proyecto',
        'Numero_face',
        'Nombre_face',
        'id_Estado_trabajo',
        'Cantidad',
        'id_Unidades',
        'Estado',
        'Creado_por',
        'Actualizado_por',
        'Fecha_creacion',
        'Fecha_actualizacion'
    ];

    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class, 'id_Proyecto', 'id_Proyecto');
    }

    public function estadoTrabajo()
    {
        return $this->belongsTo(EstadoTrabajo::class, 'id_Estado_trabajo', 'id_Estado_trabajo');
    }

    public function unidad()
    {
        return $this->belongsTo(Unidad::class, 'id_Unidades', 'id_Unidades');
    }
}
