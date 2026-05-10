<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BitacoraAsesoria extends Model
{
    use HasFactory;

    // Nombre de la tabla exacta en tu DB
    protected $table = 'bitacora_asesorias';

    // Llave primaria
    protected $primaryKey = 'id_bitacora';

    // Como no pusimos created_at y updated_at en el script SQL, desactivamos timestamps
    public $timestamps = false;

    // Campos que permitimos llenar masivamente
    protected $fillable = [
        'id_curso',
        'correo_estudiante',
        'dia',
        'hora_inicio',
        'hora_final',
        'tema',
        'resultados_compromisos'
    ];

    /**
     * Relación con el Curso
     */
    public function curso()
    {
        return $this->belongsTo(Curso::class, 'id_curso', 'id_curso');
    }

    /**
     * Relación con el Alumno (Usuario)
     * Asumiendo que tu modelo de Usuario se llama 'User'
     */
    public function alumno()
    {
        return $this->belongsTo(User::class, 'correo_estudiante', 'correo');
    }
}