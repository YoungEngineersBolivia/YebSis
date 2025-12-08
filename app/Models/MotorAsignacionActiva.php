<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MotorAsignacionActiva extends Model
{
    protected $table = 'motores_asignaciones_activas';
    protected $primaryKey = 'Id_asignacion';
    public $timestamps = true;
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $fillable = [
        'Id_motores',
        'Id_profesores',
        'Id_movimiento_salida',
        'Fecha_salida',
        'Estado_motor_salida',
        'Motivo_salida',
        'Estado_asignacion',
        'Estado_final_propuesto',
        'Trabajo_realizado',
        'Observaciones_entrega',
        'Fecha_entrega_tecnico',
        'Fecha_entrada_admin',
        'Id_usuario_entrada'
    ];

    protected $casts = [
        'Fecha_salida' => 'datetime',
        'Fecha_entrega_tecnico' => 'datetime',
        'Fecha_entrada_admin' => 'datetime',
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
     * Relación con el movimiento de salida
     */
    public function movimientoSalida(): BelongsTo
    {
        return $this->belongsTo(MotorMovimiento::class, 'Id_movimiento_salida', 'Id_movimientos');
    }

    /**
     * Relación con reportes de progreso
     */
    public function reportesProgreso(): HasMany
    {
        return $this->hasMany(ReporteProgreso::class, 'Id_asignacion', 'Id_asignacion');
    }

    /**
     * Scope para obtener solo asignaciones activas
     */
    public function scopeActivas($query)
    {
        return $query->whereIn('Estado_asignacion', ['Activa', 'Pendiente Entrada']);
    }

    /**
     * Scope para obtener solo asignaciones finalizadas
     */
    public function scopeFinalizadas($query)
    {
        return $query->where('Estado_asignacion', 'Finalizada');
    }
}