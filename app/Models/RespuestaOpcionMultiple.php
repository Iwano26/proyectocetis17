<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class RespuestaOpcionMultiple extends Model
{
    protected $table = 'respuesta_opcion_multiple';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = ['id_respuesta', 'id_opcion'];

    public function opcion()
    {
        return $this->belongsTo(OpcionPregunta::class, 'id_opcion', 'id_opcion');
    }

    public function respuesta()
    {
        return $this->belongsTo(RespuestaAlumno::class, 'id_respuesta', 'id_respuesta');
    }
}