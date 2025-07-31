<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    protected $table = 'usuarios';
    protected $primaryKey = 'Id_Usuarios';
    public $timestamps = true;
    public $incrementing = true;

    protected $fillable = [
        'Correo',
        'Contrasania',
        'Id_personas'
    ];
}

