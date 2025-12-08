<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class MotorMovimiento extends Model
{
    use HasFactory;

    protected $table = 'motores_movimientos';
    protected $primaryKey = 'Id_movimientos';

    protected $fillable = [
        'Id_motores',
        'Tipo_movimiento',
        'Fecha_movimiento',
        'Id_sucursales',
        'Id_profesores',
        'Nombre_tecnico',
        'Estado_salida',
        'Estado_entrada',
        'Motivo_salida',
        'Trabajo_realizado',
        'Observaciones',
        'Id_usuarios'
    ];

    protected $casts = [
        'Fecha_movimiento' => 'datetime'
    ];

    /**
     * Relación con Motor
     */
    public function motor(): BelongsTo
    {
        return $this->belongsTo(Motor::class, 'Id_motores', 'Id_motores');
    }

    /**
     * Relación con Profesor
     */
    public function profesor(): BelongsTo
    {
        return $this->belongsTo(Profesor::class, 'Id_profesores', 'Id_profesores');
    }

    /**
     * Relación con Sucursal
     */
    public function sucursal(): BelongsTo
    {
        return $this->belongsTo(Sucursal::class, 'Id_sucursales', 'Id_Sucursales');
    }

    /**
     * Relación con Usuario
     */
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'Id_usuarios', 'Id_usuarios');
    }

    /**
     * Relación con Asignación Activa
     * Un movimiento de salida puede tener una asignación activa asociada
     */
    public function asignacionActiva(): HasOne
    {
        return $this->hasOne(MotorAsignacionActiva::class, 'Id_movimiento_salida', 'Id_movimientos');
    }

    /**
     * Scope para movimientos de salida
     */
    public function scopeSalidas($query)
    {
        return $query->where('Tipo_movimiento', 'Salida');
    }

    /**
     * Scope para movimientos de entrada
     */
    public function scopeEntradas($query)
    {
        return $query->where('Tipo_movimiento', 'Entrada');
    }

    /**
     * Scope para movimientos de un técnico específico
     */
    public function scopeDelTecnico($query, $idProfesor)
    {
        return $query->where('Id_profesores', $idProfesor);
    }

    /**
     * Scope para movimientos recientes
     */
    public function scopeRecientes($query, $dias = 30)
    {
        return $query->where('Fecha_movimiento', '>=', now()->subDays($dias));
    }

    /**
     * Verificar si es una salida
     */
    public function esSalida(): bool
    {
        return $this->Tipo_movimiento === 'Salida';
    }

    /**
     * Verificar si es una entrada
     */
    public function esEntrada(): bool
    {
        return $this->Tipo_movimiento === 'Entrada';
    }

    /**
     * Verificar si tiene asignación activa
     */
    public function tieneAsignacionActiva(): bool
    {
        return $this->asignacionActiva()->exists();
    }
}