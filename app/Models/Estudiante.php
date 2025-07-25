<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estudiante extends Model
{
    use HasFactory;

    protected $table = 'estudiantes';
    protected $primaryKey = 'Id_estudiantes';
    public $incrementing = true;
    protected $keyType = 'int';

    // Relación con persona
    // app/Models/Estudiante.php
public function persona()
{
    return $this->belongsTo(Persona::class, 'Id_Persona', 'Id_Persona');
}

    // También podrías añadir relaciones con profesor, programa, etc.
}
