<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Tramo extends Model
{
    use HasFactory;

    protected $table = 'tramo';
    protected $primaryKey = 'id_tramo';
    public $timestamps = false;

    protected $fillable = [
        'id_aldea',
        'fecha',
        'tipo_concreto',
        'cantidad_concreto_m3',
        'supervisor',
        'temperatura',
        'observaciones',
        'Estado',
        'Creado_por',
        'Actualizado_por',
        'Fecha_creacion',
        'Fecha_actualizacion',
        'No_envio',
        'Nombre_aditivo',
        'Cantidad_lts',
        'Tipo'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->Creado_por = Auth::id();
            $model->Fecha_creacion = now();
        });

        static::updating(function ($model) {
            $model->Actualizado_por = Auth::id();
            $model->Fecha_actualizacion = now();
        });
    }

    public function aldea()
    {
        return $this->belongsTo(Aldea::class, 'id_aldea', 'id_aldea');
    }

    public function rodaduras()
    {
        return $this->belongsToMany(
            Rodadura::class,
            'tramo_elemento',
            'id_tramo',
            'id_rodadura'
        )->withPivot([
            'Estado','Creado_por','Actualizado_por','Fecha_creacion','Fecha_actualizacion'
        ]);
    }

    public function cunetas()
    {
        return $this->belongsToMany(
            Cuneta::class,
            'tramo_elemento',
            'id_tramo',
            'id_cuneta'
        )->withPivot([
            'Estado','Creado_por','Actualizado_por','Fecha_creacion','Fecha_actualizacion'
        ]);
    }
}
