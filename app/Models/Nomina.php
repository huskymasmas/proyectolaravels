<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Nomina extends Model
{
    use HasFactory;

    protected $table = 'tbl_Nomina';
    protected $primaryKey = 'id_Nomina';
    public $incrementing = true;
    public $timestamps = false;

    protected $fillable = [
        'sueldo_Base',
        'Bonos',
        'Bonos_adicional',
        'Estado',
        'Creado_por',
        'Actualizado_por',
        'Fecha_creacion',
        'Fecha_actualizacion',
    ];

    // RELACIONES
    public function empleados()
    {
        return $this->hasMany(Empleado::class, 'id_Nomina', 'id_Nomina');
    }

    public function detallesNomina()
    {
        return $this->hasMany(DetalleNomina::class, 'id_Nomina', 'id_Nomina');
    }

    public function registrosDiarios()
    {
        return $this->hasMany(RegistroDiario::class, 'id_Nomina', 'id_Nomina');
    }

    // AUDITORÍA AUTOMÁTICA
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($nomina) {
            $nomina->Creado_por = Auth::id();
            $nomina->Fecha_creacion = now();
            $nomina->Estado = 1;
        });

        static::updating(function ($nomina) {
            $nomina->Actualizado_por = Auth::id();
            $nomina->Fecha_actualizacion = now();
        });
    }
}
