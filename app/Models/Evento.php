<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Evento extends Model
{
    // Nombre de la tabla en tu BD
    protected $table = 'evento';

    // Tu llave primaria personalizada
    protected $primaryKey = 'id_evento';

    // Campos que se pueden llenar (según tu captura de pantalla)
    protected $fillable = [
        'id_curso',
        'nombre_evento',
        'fecha',
        'hora',
        'tipo'
    ];

    // Desactivamos timestamps si no tienes las columnas 'created_at' y 'updated_at'
    public $timestamps = false;

    // Relación: Un evento pertenece a un curso
    public function curso()
    {
        return $this->belongsTo(Curso::class, 'id_curso');
    }
}