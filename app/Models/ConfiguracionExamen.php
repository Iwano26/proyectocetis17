<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ConfiguracionExamen extends Model
{
    protected $table = 'configuracion_examen';
    protected $primaryKey = 'id_config';
    public $timestamps = false;

    protected $fillable = [
        'id_cuestionario', 'fecha_examen', 'hora_inicio',
        'hora_fin', 'oportunidades', 'estado'
    ];

    public function cuestionario()
    {
        return $this->belongsTo(Cuestionario::class, 'id_cuestionario', 'id_cuestionario');
    }
}