<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RespuestaForo extends Model
{
    protected $table = 'respuesta_foro';
    protected $primaryKey = 'id_respuesta_foro';
    public $timestamps = false;

    protected $fillable = [
        'id_pregunta_foro',
        'correo_persona',
        'texto_respuesta',
        'fecha_respuesta'
    ];

    // Pertenece a una pregunta específica
    public function pregunta()
    {
        return $this->belongsTo(PreguntaForo::class, 'id_pregunta_foro', 'id_pregunta_foro');
    }

    // Quién respondió
    public function autor()
    {
        return $this->belongsTo(User::class, 'correo_persona', 'correo');
    }
}