<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Aldea extends Model
{
    use HasFactory;

    protected $table = 'tbl_aldea';
    protected $primaryKey = 'id_aldea';
    public $timestamps = false;

    protected $fillable = [
        'Nombre','id_Proyecto','Estado','Creado_por','Actualizado_por','Fecha_creacion','Fecha_actualizacion'
    ];

    public function tramos()
    {
        return $this->hasMany(Tramo::class, 'id_aldea', 'id_aldea');
    }

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
}
