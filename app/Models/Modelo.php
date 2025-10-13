<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Modelo extends Model
{
    // Nombre de la tabla
    protected $table = 'modelos';

    // Clave primaria
    protected $primaryKey = 'Id_modelos';

    // Para permitir asignación masiva (opcional)
    protected $fillable = [
        'Nombre_modelo',
        'Id_programa'
    ];

    // Relación con Programa
    public function programa()
    {
        return $this->belongsTo(Programa::class, 'Id_programa', 'Id_programas');
    }

    // Relación inversa con Estudiantes
    public function estudiantes()
    {
        return $this->hasMany(Estudiante::class, 'Id_modelo', 'Id_modelos');
    }
}
