<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BodegaGeneral extends Model
{
    use HasFactory;

    // Nombre de la tabla si no sigue la convención plural de Laravel
    protected $table = 'tbl_bodega_general';

    // Clave primaria
    protected $primaryKey = 'id_Bodega_general';

    // Si tu clave primaria es auto-incremental
    public $incrementing = true;

    // Tipo de la clave primaria
    protected $keyType = 'int';

    // Si no quieres que Laravel maneje automáticamente timestamps
    public $timestamps = false;

    // Campos que se pueden asignar masivamente
    protected $fillable = [
        'Nombre',
        'Descripcion',
        'Cantidad',
        'id_Unidades',
        'Estado',
        'Creado_por',
        'Actualizado_por',
        'Fecha_creacion',
        'Fecha_actualizacion',
        'stock_minimo',
    ];

    // Si quieres, puedes definir relaciones. Por ejemplo, con la tabla de Unidades:
    public function unidad()
    {
        return $this->belongsTo(Unidad::class, 'id_Unidades');
    }
}
