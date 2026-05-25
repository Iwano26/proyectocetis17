<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EvidenciaAsesoria extends Model
{
    protected $table = 'evidencia_asesoria';
    protected $primaryKey = 'id_evidencia';
    public $timestamps = false;

    protected $fillable = [
        'id_asesoria',
        'correo_alumno',
        'archivo',
        'fecha_subida',
    ];

    public function asesoria()
    {
        return $this->belongsTo(Asesoria::class, 'id_asesoria', 'id_asesoria');
    }

    public function alumno()
    {
        return $this->belongsTo(\App\Models\Usuario::class, 'correo_alumno', 'correo');
    }
}
