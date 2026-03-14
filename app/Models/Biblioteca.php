<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Biblioteca extends Model
{
    use HasFactory;

    protected $table = 'biblioteca';
    protected $primaryKey = 'id_biblioteca'; // Ajustado según tu imagen de tabla

    protected $fillable = [
        'id_curso',
        'correo_usuario',
        'nombre_doc',
        'materia',
        'ruta_archivo',
        'autor'
    ];

    // Si tu tabla tiene created_at y updated_at, déjalo en true
    public $timestamps = true; 
}