<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'persona';
    protected $primaryKey = 'correo';
    public $incrementing = false; 
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'correo',
        'nombre',
        'apellidoPa',
        'apellidoMa',
        'rol',
        'telefono',
        'pass',
        'activo',
        'confirmado',         // Nueva columna añadida
        'token_confirmacion', // Nueva columna añadida
    ];

    protected $hidden = [
        'pass',
        'remember_token',
    ];

    public function getAuthIdentifierName()
    {
        return 'correo';
    }

    public function getKey()
    {
        return $this->getAttribute($this->primaryKey);
    }

    public function getAuthPassword()
    {
        return $this->pass;
    }

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'pass' => 'hashed', 
            'correo' => 'string',
            'confirmado' => 'integer',
        ];
    }
}