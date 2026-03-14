<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Horario extends Model
{
    use HasFactory;

    // Hacemos referencia a la tabla que creamos en la base de datos
    protected $table = 'curso_horarios';

    // Definimos la llave primaria si no es el estándar 'id'
    protected $primaryKey = 'id_horario';

    // Campos que se pueden llenar de forma masiva
    protected $fillable = [
        'id_curso',
        'dia_semana',
        'hora_inicio',
        'hora_fin'
    ];

    /**
     * Relación: Muchos horarios pertenecen a un solo curso.
     */
    public function curso()
    {
        return $this->belongsTo(Curso::class, 'id_curso', 'id_curso');
    }
}