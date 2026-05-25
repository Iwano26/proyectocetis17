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

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'correo_usuario', 'correo');
    }
    
    public function curso()
    {
        return $this->belongsTo(Curso::class, 'id_curso', 'id_curso');
    }
}