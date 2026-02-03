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
        'id_aldea',
        'numero_face',
        'nombre_face',
        'id_Estado_trabajo',
        'cantidad',
        'id_Unidades',
        'CostoQ',
        'estado',
        'creado_por',
        'actualizado_por',
        'fecha_creacion',
        'fecha_actualizacion'
    ];

    // Relaciones
    public function aldea()
    {
        return $this->belongsTo(Aldea::class, 'id_aldea', 'id_aldea');
    }
public function estadoTrabajo()
{
    return $this->belongsTo(EstadoTrabajo::class, 'id_Estado_trabajo', 'id_Estado_trabajo');
}

    public function unidad()
    {
        return $this->belongsTo(Unidad::class, 'id_Unidades', 'id_Unidades');
    }
    public function planos()
    {
        return $this->hasMany(Plano::class, 'id_trabajo');
    }
}
