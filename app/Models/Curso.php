<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Curso extends Model
{
    //hacemos  referencia a la tabla usuarios

    protected $table = 'curso';
    protected $primaryKey = 'id_curso';
    public $timestamps = false;

  //hacer que los campos sean editables

    protected $fillable = [

        'id_curso',
        'correo_persona',
        'nombre_curso',
        'fecha_inicio',
        'materia',
        'fecha_fin',
        'horas_disponibles',
        'estado'

    ];
    public function correoPersona()
    {
       return $this->belongsToMany(Usuario::class, 'persona', 'correo', 'correo_persona');
    }

    public function horarios() {
        
        return $this->hasMany(Horario::class, 'id_curso');
    }

}