<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    protected $table = 'tbl_usuario';
    protected $primaryKey = 'id_usuario';
    public $timestamps = false;

    protected $fillable = [
        'nombre','Apellido','Sexo','Fecha_nacimiento','Fecha_inicio',
        'Fecha_final','Estado','Creado_por','Actualizado_por','Fecha_creacion','Fecha_actualizacion'
    ];

    // Relaciones
    public function creadorConfiguraciones(){ return $this->hasMany(Configuracion::class, 'Creado_por'); }
    public function creadorDosificaciones(){ return $this->hasMany(Dosificacion::class, 'Creado_por'); }
    public function creadorMateriales(){ return $this->hasMany(Material::class, 'Creado_por'); }
    public function creadorProyectos(){ return $this->hasMany(Proyecto::class, 'Creado_por'); }
    public function creadorAuditorias(){ return $this->hasMany(AuditoriaEvento::class, 'Creado_por'); }
}
