<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Biblioteca extends Model
{
    use HasFactory;

    // Indicamos el nombre exacto de la tabla que hiciste en DBeaver
    protected $table = 'biblioteca';

    // Lista de campos que se pueden llenar (coinciden con tu SQL)
    protected $fillable = [
        'nombre_doc',
        'materia',
        'ruta_archivo',
        'autor'
    ];
}