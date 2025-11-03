<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequerimientoMaterial extends Model
{
    use HasFactory;

    protected $table = 'tbl_requerimiento_material';
    protected $primaryKey = 'id_requerimiento_material';

    protected $fillable = [
        'id_Dosificacion',
        'id_Detalle_obra',
        'Cemento_kg',
        'Cemento_sacos',
        'Arena_m3',
        'Arena_kg',
        'Piedrin_m3',
        'Piedrin_kg',
        'Aditivo_l',
        'Creado_por',
        'Actualizado_por',
        'Fecha_creacion',
        'Fecha_actualizacion'
    ];

    public $timestamps = false;

    // Relaciones
    public function dosificacion()
    {
        return $this->belongsTo(Dosificacion::class, 'id_Dosificacion');
    }

    public function detalleObra()
    {
        return $this->belongsTo(DetalleObra::class, 'id_Detalle_obra');
    }

}
