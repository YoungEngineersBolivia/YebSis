<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Estudiante;
use App\Models\Profesor;
use App\Models\Programa;

class Asistencia extends Model
{
    protected $table = 'asistencias';
    protected $primaryKey = 'Id_asistencia';

    protected $fillable = [
        'Id_estudiantes',
        'Id_profesores',
        'Id_programas',
        'Fecha',
        'Estado',
        'Observacion',
        'Fecha_reprogramada'
    ];

    public function estudiante()
    {
        return $this->belongsTo(Estudiante::class, 'Id_estudiantes', 'Id_estudiantes');
    }

    public function profesor()
    {
        return $this->belongsTo(Profesor::class, 'Id_profesores', 'Id_profesores');
    }

    public function programa()
    {
        return $this->belongsTo(Programa::class, 'Id_programas', 'Id_programas');
    }
}
