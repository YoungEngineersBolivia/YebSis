<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReporteProgreso extends Model
{
    use HasFactory;

    protected $table = 'reportes_progreso';
    protected $primaryKey = 'Id_reporte';

    protected $fillable = [
        'Id_asignacion',
        'Fecha_reporte',
        'Estado_actual',
        'Descripcion_trabajo',
        'Observaciones'
    ];

    protected $casts = [
        'Fecha_reporte' => 'datetime'
    ];

    /**
     * Relación con Asignación
     */
    public function asignacion(): BelongsTo
    {
        return $this->belongsTo(MotorAsignacionActiva::class, 'Id_asignacion', 'Id_asignacion');
    }

    /**
     * Scope para reportes recientes
     */
    public function scopeRecientes($query, $dias = 7)
    {
        return $query->where('Fecha_reporte', '>=', now()->subDays($dias));
    }

    /**
     * Scope para reportes por estado
     */
    public function scopePorEstado($query, $estado)
    {
        return $query->where('Estado_actual', $estado);
    }

    /**
     * Verificar si el motor fue reparado
     */
    public function estaReparado(): bool
    {
        return $this->Estado_actual === 'Reparado';
    }

    /**
     * Verificar si es irreparable
     */
    public function esIrreparable(): bool
    {
        return $this->Estado_actual === 'Irreparable';
    }

    /**
     * Verificar si está en proceso
     */
    public function enProceso(): bool
    {
        return in_array($this->Estado_actual, ['En Diagnostico', 'En Reparacion']);
    }
}