<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Usuario extends Authenticatable
{
    use Notifiable;

    protected $table = 'persona';
    protected $primaryKey = 'correo'; 
    public $incrementing = false;     
    protected $keyType = 'string';    
    public $timestamps = false; 

    protected $fillable = [
        'correo','pass','nombre','apellidoPa','apellidoMa','rol','telefono','activo'
    ];

    protected $hidden = [
        'pass', 
        'remember_token'
    ];

    public function getAuthPassword()
    {
        return $this->pass;
    }
}