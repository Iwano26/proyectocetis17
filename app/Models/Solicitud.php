<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Solicitud extends Model
{
    protected $table = 'solicitud';
    protected $primaryKey = 'id_solicitud';
    public $timestamps = false;

    protected $fillable = [
        'id_curso',
        'id_evento',
        'correo_alumno',
        'motivo',
        'dia_sugerido',
        'hora_sugerida',
        'estado',
        'fecha_solicitud',
    ];

    public function curso()
    {
        return $this->belongsTo(Curso::class, 'id_curso', 'id_curso');
    }

    public function alumno()
    {
        return $this->belongsTo(\App\Models\Usuario::class, 'correo_alumno', 'correo');
    }
}