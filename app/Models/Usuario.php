<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Usuario extends Authenticatable
{
    use Notifiable;

    // Mapea la tabla 'usuarios' creada en tu migration
    protected $table = 'usuarios';
    protected $primaryKey = 'Id_usuarios';
    public $timestamps = true;
    public $incrementing = true;
    protected $keyType = 'int';

    // Nombre del campo que contiene la contraseña en tu tabla
    public function getAuthPassword()
    {
        return $this->Contrasenia;
    }

    // Opcional: evita que se asigne masivamente campos no deseados
    protected $guarded = [];

    // Relación con persona
    public function persona()
    {
        return $this->belongsTo(\App\Models\Persona::class, 'Id_personas', 'Id_personas');
    }
}
