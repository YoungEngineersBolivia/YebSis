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

    protected $fillable = [
        'Cod_estudiante',
        'Estado',
        'Id_Personas',
        'Id_profesores',
        'Id_programas',
        'Id_sucursales',
    ];

    public function persona()
    {
        return $this->belongsTo(Persona::class, 'Id_Personas', 'Id_personas');
    }
}
