<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Asesoria extends Model
{
    // Le decimos el nombre exacto de tu tabla
    protected $table = 'asesoria';
    
    // Tu llave primaria
    protected $primaryKey = 'id_asesoria';
    
    // Desactivamos los timestamps porque tu tabla no tiene 'created_at' ni 'updated_at'
    public $timestamps = false;

    // Los campos que permitiremos registrar y editar (¡Aquí va el estado!)
    protected $fillable = [
        'id_evento',
        'lugar',
        'fecha_asesoria',
        'hora_inicio',
        'hora_fin',
        'estado'
    ];
}