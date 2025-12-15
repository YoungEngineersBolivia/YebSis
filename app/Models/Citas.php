<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Citas extends Model
{
    use HasFactory;

    protected $table = 'citas';
    protected $primaryKey = 'Id_citas';

    protected $fillable = [
        'Fecha',
        'Hora',
        'motivo',
        'estado',
        'Id_tutores',
        'Id_estudiantes',
        'Id_evaluaciones'
    ];

    protected $casts = [
        'Fecha' => 'date',
        'Hora' => 'datetime:H:i',
    ];

    /**
     * Relación con Tutor
     */
    public function tutor()
    {
        return $this->belongsTo(Tutores::class, 'Id_tutores', 'Id_tutores');
    }

    /**
     * Relación con Estudiante
     */
    public function estudiante()
    {
        return $this->belongsTo(Estudiante::class, 'Id_estudiantes', 'Id_estudiantes');
    }

    /**
     * Relación con Evaluación (opcional)
     */
    public function evaluacion()
    {
        // Si tu modelo se llama Evaluacion (singular), usa Evaluacion::class
        return $this->belongsTo(Evaluacion::class, 'Id_evaluaciones', 'Id_evaluaciones');
    }

    /**
     * Scope para obtener citas pendientes
     */
    public function scopePendientes($query)
    {
        return $query->where('estado', 'pendiente');
    }

    /**
     * Scope para obtener citas completadas
     */
    public function scopeCompletadas($query)
    {
        return $query->where('estado', 'completada');
    }

    /**
     * Scope para obtener citas de una fecha específica
     */
    public function scopeDeFecha($query, $fecha)
    {
        return $query->whereDate('Fecha', $fecha);
    }

    /**
     * Scope para obtener citas futuras
     */
    public function scopeFuturas($query)
    {
        return $query->where('Fecha', '>=', now()->format('Y-m-d'));
    }
    
    /**
     * Scope para obtener citas pasadas
     */
    public function scopePasadas($query)
    {
        return $query->where('Fecha', '<', now()->format('Y-m-d'));
    }
}