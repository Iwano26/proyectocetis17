<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Cuestionario extends Model
{
    protected $table = 'cuestionario';
    protected $primaryKey = 'id_cuestionario';
    public $timestamps = false;

    protected $fillable = [
        'id_evento', 'nombre_cuestionario', 'fecha_creacion'
    ];

    public function preguntas()
    {
        return $this->hasMany(Pregunta::class, 'id_cuestionario', 'id_cuestionario')
                    ->orderBy('orden');
    }

    public function configuracion()
    {
        return $this->hasOne(ConfiguracionExamen::class, 'id_cuestionario', 'id_cuestionario');
    }

    public function intentos()
    {
        return $this->hasMany(IntentoExamen::class, 'id_cuestionario', 'id_cuestionario');
    }

    public function evento()
    {
        return $this->belongsTo(Evento::class, 'id_evento', 'id_evento');
    }
}