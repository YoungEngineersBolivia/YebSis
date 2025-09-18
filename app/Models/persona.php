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

    public function rol()
    {
        return $this->belongsTo(\App\Models\Rol::class, 'Id_roles', 'Id_roles');
    }
    public function tutor()
    {
        return $this->hasOne(Tutores::class, 'Id_personas');
    }
}
