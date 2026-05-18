<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Pregunta extends Model
{
    protected $table = 'pregunta';
    protected $primaryKey = 'id_pregunta';
    public $timestamps = false;

    protected $fillable = [
        'id_cuestionario', 'texto_pregunta', 'tipo', 'orden'
    ];

    public function opciones()
    {
        return $this->hasMany(OpcionPregunta::class, 'id_pregunta', 'id_pregunta');
    }

    public function cuestionario()
    {
        return $this->belongsTo(Cuestionario::class, 'id_cuestionario', 'id_cuestionario');
    }

    public function respuestas()
    {
        return $this->hasMany(RespuestaAlumno::class, 'id_pregunta', 'id_pregunta');
    }
}