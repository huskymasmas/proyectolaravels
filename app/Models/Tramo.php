<?php
// App/Models/Tramo.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tramo extends Model
{
    protected $table = 'tramo';
    protected $primaryKey = 'id_tramo';
    public $timestamps = false;

    protected $fillable = [
        'id_Proyecto','No_envio','fecha','tipo_concreto','cantidad_concreto_m3',
        'supervisor','temperatura','Nombre_aditivo','Cantidad_lts','Tipo',
        'observaciones','Estado','Creado_por','Fecha_creacion'
    ];

    // 🔹 Relación con Proyecto
    public function proyecto()
    {
            return $this->belongsTo(Proyecto::class, 'id_Proyecto', 'id_Proyecto');

    }

    // 🔹 Relación con TramoElemento (para rodaduras)
    public function rodaduras()
    {
        return $this->hasManyThrough(
            Rodadura::class,
            TramoElemento::class,
            'id_tramo',     // FK en TramoElemento hacia Tramo
            'id_rodadura',  // FK en Rodadura
            'id_tramo',     // PK de Tramo
            'id_rodadura'   // PK de TramoElemento
        );
    }

    // 🔹 Relación con TramoElemento (para cunetas)
    public function cunetas()
    {
        return $this->hasManyThrough(
            Cuneta::class,
            TramoElemento::class,
            'id_tramo',    // FK en TramoElemento hacia Tramo
            'id_cuneta',   // FK en Cuneta
            'id_tramo',    // PK de Tramo
            'id_cuneta'    // PK de TramoElemento
        );
    }
    
}
