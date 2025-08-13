<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Graduados extends Model
{
    use HasFactory;

    protected $table = 'graduados';
    protected $primaryKey = 'Id_graduado';

    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'Fecha_graduado',
        'Id_estudiantes',
        'Id_programas',
        'Id_profesores',
    ];

    public function estudiante()
    {
        return $this->belongsTo(Estudiante::class, 'Id_estudiantes', 'Id_estudiantes');
    }

    public function programa()
    {
        return $this->belongsTo(Programa::class, 'Id_programas', 'Id_programas');
    }

    public function profesor()
    {
        return $this->belongsTo(Profesor::class, 'Id_profesores', 'Id_profesores');
    }
}
