<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evaluacion extends Model
{
    use HasFactory;

    protected $table = 'evaluaciones';
    protected $primaryKey = 'Id_evaluaciones';
    public $incrementing = true;
    protected $keyType = 'int';

    public $timestamps = true;

    protected $fillable = [
        'fecha_evaluacion',
        'Id_estudiantes',
        'Id_preguntas',
        'Id_respuestas',
        'Id_modelos',
        'Id_profesores',
        'Id_programas',
    ];

    // Relaciones
    public function estudiante()
    {
        return $this->belongsTo(Estudiante::class, 'Id_estudiantes', 'Id_estudiantes');
    }

    public function pregunta()
    {
        return $this->belongsTo(Pregunta::class, 'Id_preguntas', 'Id_preguntas');
    }

    public function respuesta()
    {
        return $this->belongsTo(Respuesta::class, 'Id_respuestas', 'Id_respuestas');
    }

    public function modelo()
    {
        return $this->belongsTo(Modelo::class, 'Id_modelos', 'Id_modelos');
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
