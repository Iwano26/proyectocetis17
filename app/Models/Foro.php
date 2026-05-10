<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Foro extends Model
{
    use HasFactory;

    protected $table = 'foro';
    protected $primaryKey = 'id_foro';
    public $timestamps = false; // <--- Añade esta línea

    protected $fillable = [
        'id_curso',
        'nombre_foro',
        'descripcion'
    ];

    // Un foro pertenece a un curso
    public function curso()
    {
        return $this->belongsTo(Curso::class, 'id_curso', 'id_curso');
    }

    // Un foro tiene muchas preguntas
    public function preguntas()
    {
        return $this->hasMany(PreguntaForo::class, 'id_foro', 'id_foro');
    }
}