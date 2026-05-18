<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class OpcionPregunta extends Model
{
    protected $table = 'opcion_pregunta';
    protected $primaryKey = 'id_opcion';
    public $timestamps = false;

    protected $fillable = [
        'id_pregunta', 'texto_opcion', 'es_correcta'
    ];

    public function pregunta()
    {
        return $this->belongsTo(Pregunta::class, 'id_pregunta', 'id_pregunta');
    }
}