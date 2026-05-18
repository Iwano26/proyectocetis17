<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class RespuestaAlumno extends Model
{
    protected $table = 'respuesta_alumno';
    protected $primaryKey = 'id_respuesta';
    public $timestamps = false;

    protected $fillable = [
        'id_intento', 'id_pregunta', 'id_opcion',
        'texto_respuesta', 'es_correcta', 'revisada'
    ];

    public function intento()
    {
        return $this->belongsTo(IntentoExamen::class, 'id_intento', 'id_intento');
    }

    public function pregunta()
    {
        return $this->belongsTo(Pregunta::class, 'id_pregunta', 'id_pregunta');
    }

    public function opcion()
    {
        return $this->belongsTo(OpcionPregunta::class, 'id_opcion', 'id_opcion');
    }

    public function opcionesMultiples()
    {
        return $this->hasMany(RespuestaOpcionMultiple::class, 'id_respuesta', 'id_respuesta');
    }
}