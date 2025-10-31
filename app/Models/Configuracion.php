<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Configuracion extends Model
{
    protected $table = 'tbl_Configuracion';
    protected $primaryKey = 'id_Configuracion';
    public $timestamps = false;

    protected $fillable = [
        'Parametros',
        'Valor',
        'NOTAS',
        'id_Proyecto', // 👈 campo para filtro
        'Creado_por',
        'Actualizado_por',
        'Fecha_creacion',
        'Fecha_actualizacion'
    ];

    // Relación con proyectos
    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class, 'id_Proyecto');
    }
}
