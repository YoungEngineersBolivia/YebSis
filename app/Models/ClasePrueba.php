<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClasePrueba extends Model
{
    protected $table = 'clasePrueba';
    protected $primaryKey = 'Id_clasePrueba';
    public $timestamps = false;

    protected $fillable = [
        'Nombre_Estudiante',
        'Fecha_clase',
        'Hora_clase',
        'Comentarios',
        'Id_prospectos',
    ];
}