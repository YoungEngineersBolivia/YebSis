<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class MotorAsignacionActiva extends Model
{
    use HasFactory;

    protected $table = 'motores_asignaciones_activas';
    protected $primaryKey = 'Id_asignacion';

    protected $fillable = [
        'Id_motores',
        'Id_profesores',
        'Id_movimiento_salida',
        'Fecha_salida',
        'Estado_motor_salida',
        'Motivo_salida',
        'Estado_asignacion'
    ];

    protected $casts = [
        'Fecha_salida' => 'datetime'
    ];

    /**
     * Relación con Motor
     */
    public function motor(): BelongsTo
    {
        return $this->belongsTo(Motor::class, 'Id_motores', 'Id_motores');
    }

    /**
     * Relación con Profesor (Técnico)
     */
    public function profesor(): BelongsTo
    {
        return $this->belongsTo(Profesor::class, 'Id_profesores', 'Id_profesores');
    }

    /**
     * Relación con Movimiento de Salida
     */
    public function movimientoSalida(): BelongsTo
    {
        return $this->belongsTo(MotorMovimiento::class, 'Id_movimiento_salida', 'Id_movimientos');
    }

    /**
     * Relación con Reportes de Progreso
     */
    public function reportesProgreso(): HasMany
    {
        return $this->hasMany(ReporteProgreso::class, 'Id_asignacion', 'Id_asignacion')
            ->orderBy('Fecha_reporte', 'desc');
    }

    /**
     * Obtener el último reporte
     */
    public function ultimoReporte(): HasOne
    {
        return $this->hasOne(ReporteProgreso::class, 'Id_asignacion', 'Id_asignacion')
            ->latest('Fecha_reporte');
    }

    /**
     * Scope para asignaciones activas
     */
    public function scopeActivas($query)
    {
        return $query->where('Estado_asignacion', 'Activa');
    }

    /**
     * Scope para asignaciones finalizadas
     */
    public function scopeFinalizadas($query)
    {
        return $query->where('Estado_asignacion', 'Finalizada');
    }

    /**
     * Scope para asignaciones de un técnico
     */
    public function scopeDelTecnico($query, $idProfesor)
    {
        return $query->where('Id_profesores', $idProfesor);
    }

    /**
     * Verificar si está activa
     */
    public function estaActiva(): bool
    {
        return $this->Estado_asignacion === 'Activa';
    }

    /**
     * Verificar si está finalizada
     */
    public function estaFinalizada(): bool
    {
        return $this->Estado_asignacion === 'Finalizada';
    }

    /**
     * Obtener días transcurridos desde la asignación
     */
    public function diasTranscurridos(): int
    {
        return $this->Fecha_salida->diffInDays(now());
    }
}