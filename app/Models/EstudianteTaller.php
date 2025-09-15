<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstudianteTaller extends Model
{
    use HasFactory;

    protected $table = 'estudiantes_talleres';
    protected $primaryKey = 'Id_estudiantes_talleres';

    protected $fillable = [
        'Id_estudiantes',
        'Id_programas',
        'Fecha_inscripcion',
        'Estado_inscripcion',
        'Observaciones'
    ];

    protected $casts = [
        'Fecha_inscripcion' => 'date'
    ];

    // Relación con Estudiante
    public function estudiante()
    {
        return $this->belongsTo(Estudiante::class, 'Id_estudiantes', 'Id_estudiantes');
    }

    // Relación con Programa (que será un taller)
    public function taller()
    {
        return $this->belongsTo(Programa::class, 'Id_programas', 'Id_programas');
    }

    // Relación con Pagos de Taller
    public function pagosTaller()
    {
        return $this->hasMany(PagoTaller::class, 'Id_estudiantes_talleres', 'Id_estudiantes_talleres');
    }

    // Scope para obtener solo inscripciones activas
    public function scopeActivos($query)
    {
        return $query->where('Estado_inscripcion', 'inscrito');
    }

    // Scope para obtener inscripciones de talleres de invierno
    public function scopeTalleresInvierno($query)
    {
        return $query->whereHas('taller', function($q) {
            $q->where('Tipo', 'taller_invierno');
        });
    }

    // Scope para obtener inscripciones de talleres de verano
    public function scopeTalleresVerano($query)
    {
        return $query->whereHas('taller', function($q) {
            $q->where('Tipo', 'taller_verano');
        });
    }
}
