<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Utilidades extends Model
{
    protected $table = 'tbl_utilidades';
    protected $primaryKey = 'id_utilidades';
    public $timestamps = false;

    protected $fillable = [
        'id_Proyecto','Valor','id_Unidades','Detalle','Calculo','Resultado','Descripcion',
        'id_Requerimiento_carrpeta_rodadura','id_Requerimiento_cuetas'
    ];

    public function proyecto(){ return $this->belongsTo(Proyecto::class, 'id_Proyecto'); }
    public function unidad(){ return $this->belongsTo(Unidad::class, 'id_Unidades'); }
    public function requerimientoCarrpeta(){ return $this->belongsTo(RequerimientoCarrpetaRodadura::class, 'id_Requerimiento_carrpeta_rodadura'); }
    public function requerimientoCuetas(){ return $this->belongsTo(RequerimientoCuetas::class, 'id_Requerimiento_cuetas'); }
}
