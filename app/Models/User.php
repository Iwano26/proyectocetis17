<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // 1. Especifica el nombre de tu tabla (si no se llama 'users')
    // protected $table = 'usuarios'; 

    // 2. Definir la llave primaria (en tu imagen parece ser 'correo')
    protected $table = 'persona';
    protected $primaryKey = 'correo';
    public $incrementing = false; // Como es un string, desactivamos el autoincremento
    protected $keyType = 'string';

    public $timestamps = false;

    /**
     * Los atributos que se pueden asignar masivamente.
     */
    protected $fillable = [
        'correo',
        'nombre',
        'apellidoPa',
        'apellidoMa',
        'rol',
        'telefono',
        'pass', // Usamos 'pass' según tu esquema
    ];

    /**
     * Los atributos que deben ocultarse para la serialización.
     */
    protected $hidden = [
        'pass',
        'remember_token',
    ];


    /**
     * Indica el nombre de la columna que sirve como identificador único (tu llave primaria).
     */
    public function getAuthIdentifierName()
    {
        return 'correo';
    }



    public function getKey()
{
    return $this->getAttribute($this->primaryKey);
}
    /**
     * Mapear el campo de contraseña para que Laravel sepa 
     * que la contraseña no se llama 'password' en la DB.
     */
    public function getAuthPassword()
    {
        return $this->pass;
    }

    /**
     * Los atributos que deben ser casteados.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'pass' => 'hashed', 
            'correo' => 'string',
        ];
    }
}