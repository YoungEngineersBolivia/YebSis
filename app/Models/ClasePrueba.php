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
        'Id_profesores',
        'Asistencia',
    ];

    /**
     * Relación con Profesor
     */
    public function profesor()
    {
        return $this->belongsTo(Profesor::class, 'Id_profesores', 'Id_profesores');
    }

    /**
     * Relación con Prospecto
     */
    public function prospecto()
    {
        return $this->belongsTo(Prospecto::class, 'Id_prospectos', 'Id_prospectos');
    }
}