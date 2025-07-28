<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    protected $table = 'personas';
    protected $primaryKey = 'Id_personas';
    public $timestamps = true;

    protected $fillable = [
        'Nombre',
        'Apellido',
        'Genero',
        'Direccion_domicilio',
        'Fecha_nacimiento',
        'Fecha_registro',
        'Celular',
        'Id_roles'
    ];
}
