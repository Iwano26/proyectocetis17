<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AsistenciaAsesoria extends Model
{
    protected $table = 'asistencia_asesoria';
    protected $primaryKey = 'id_asistencia';
    public $timestamps = false; // No usa created_at ni updated_at

    protected $fillable = [
        'id_asesoria',
        'correo_persona',
        'asistio'
    ];
}