<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PreguntaForo extends Model
{
    protected $table = 'pregunta_foro';
    protected $primaryKey = 'id_pregunta_foro';
    public $timestamps = false; // Ya que usas 'fecha_pregunta' manualmente

    protected $fillable = [
        'id_foro',
        'correo_persona',
        'texto_pregunta',
        'fecha_pregunta'
    ];

    // Relación con el Foro padre
    public function foro()
    {
        return $this->belongsTo(Foro::class, 'id_foro', 'id_foro');
    }

    // Una pregunta tiene muchas respuestas
    public function respuestas()
    {
        return $this->hasMany(RespuestaForo::class, 'id_pregunta_foro', 'id_pregunta_foro');
    }

    // Quién hizo la pregunta (Relación con Persona)
    public function autor()
    {
        return $this->belongsTo(User::class, 'correo_persona', 'correo');
    }
}