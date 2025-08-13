<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Horario extends Model
{
    protected $table = 'horarios';
    protected $primaryKey = 'Id_horarios';
    public $timestamps = true;

    protected $fillable = [
        'Horario_clase_uno',
        'Dia_clase_uno',
        'Horario_clase_dos',
        'Dia_clase_dos',
        'Id_programas',
        'Id_profesores',
        'Id_estudiantes',
    ];

    public function estudiante()
    {
        return $this->belongsTo(Estudiante::class, 'Id_estudiantes', 'Id_estudiantes')/*->withDefault()*/;
    }

    public function profesor()
    {
        return $this->belongsTo(Profesor::class, 'Id_profesores', 'Id_profesores')/*->withDefault()*/;
    }

    public function programa()
    {
        return $this->belongsTo(Programa::class, 'Id_programas', 'Id_programas')/*->withDefault()*/;
    }
}
