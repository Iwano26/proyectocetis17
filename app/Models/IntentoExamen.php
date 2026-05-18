<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class IntentoExamen extends Model
{
    protected $table = 'intento_examen';
    protected $primaryKey = 'id_intento';
    public $timestamps = false;

    protected $fillable = [
        'id_cuestionario', 'correo_estudiante', 'numero_intento',
        'calificacion', 'fecha_inicio', 'fecha_fin', 'completado'
    ];

    public function cuestionario()
    {
        return $this->belongsTo(Cuestionario::class, 'id_cuestionario', 'id_cuestionario');
    }

    public function respuestas()
    {
        return $this->hasMany(RespuestaAlumno::class, 'id_intento', 'id_intento');
    }
}