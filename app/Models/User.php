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
    protected $primaryKey = 'correo';
    public $incrementing = false; // Como es un string, desactivamos el autoincremento
    protected $keyType = 'string';

    /**
     * Los atributos que se pueden asignar masivamente.
     */
    protected $fillable = [
        'correo',
        'nombre',
        'apellidoPa',
        'apellidoMa',
        'rol',
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
            'pass' => 'hashed', // Laravel tratará 'pass' como una contraseña cifrada
        ];
    }
}