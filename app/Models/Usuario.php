<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable; // IMPORTANTE
use Illuminate\Notifications\Notifiable;

class Usuario extends Authenticatable
{
    use Notifiable;

    protected $table = 'usuarios';
    protected $primaryKey = 'Id_usuarios';
    public $timestamps = true;
    public $incrementing = true;

    protected $fillable = [
        'Correo',
        'Contrasania',
        'Id_personas'
    ];

    protected $hidden = [
        'Contrasania',
        'remember_token'
    ];

    // Indicar la columna de contraseña para Auth
    public function getAuthPassword()
    {
        return $this->Contrasania;
    }

    // Relación con persona
    public function persona()
    {
        return $this->belongsTo(\App\Models\Persona::class, 'Id_personas', 'Id_personas');
    }
}
