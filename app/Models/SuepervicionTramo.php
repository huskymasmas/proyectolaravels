<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuepervicionTramo extends Model
{
    use HasFactory;

    protected $table = 'tbl_Suepervicion_Tramo';
    protected $primaryKey = 'id_Suepervicion_Tramo';
    public $timestamps = false;

    protected $fillable = [
        'id_Rodadura_Control', 'id_Control_supervicion_Tramo', 'Estado',
        'Creado_por', 'Actualizado_por', 'Fecha_creacion', 'Fecha_actualizacion'
    ];

    public function rodadura() {
        return $this->belongsTo(ControlEjes::class, 'id_Rodadura_Control');
    }

    public function controlTramo() {
        return $this->belongsTo(ControlSupervicionTramo::class, 'id_Control_supervicion_Tramo');
    }
}
