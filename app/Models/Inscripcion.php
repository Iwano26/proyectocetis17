<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inscripcion extends Model
{
    // 1. Nombre de la tabla en DBeaver
    protected $table = 'inscripcion';

    // 2. Tu llave primaria personalizada
    protected $primaryKey = 'id_inscripcion';

    // 3. Campos que se pueden llenar (Mass Assignment)
    protected $fillable = [
        'id_curso',
        'correo_estudiante',
        'fecha_inscripcion'
    ];

    // 4. Desactivamos timestamps porque no creamos created_at ni updated_at
    public $timestamps = false;

    // RELACIONES:

    // Una inscripción pertenece a un Curso
    public function curso()
    {
        return $this->belongsTo(Curso::class, 'id_curso');
    }

    // Una inscripción pertenece a una Persona (el estudiante)
    public function estudiante()
    {
        return $this->belongsTo(Usuario::class, 'correo_estudiante', 'correo');
    }
}