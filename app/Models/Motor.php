<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Motor extends Model
{
    use HasFactory;

    protected $table = 'motores';
    protected $primaryKey = 'Id_motores';

    protected $fillable = [
        'Id_motor',
        'Estado',
        'Ubicacion_actual',
        'Observacion',
        'Id_sucursales',
        'Id_tecnico_actual'
    ];

    /**
     * Relación con Sucursales
     */
    public function sucursal(): BelongsTo
    {
        return $this->belongsTo(Sucursal::class, 'Id_sucursales', 'Id_Sucursales');
    }

    /**
     * Relación con Técnico Actual
     */
    public function tecnicoActual(): BelongsTo
    {
        return $this->belongsTo(Profesor::class, 'Id_tecnico_actual', 'Id_profesores');
    }

    /**
     * Relación con Movimientos
     */
    public function movimientos(): HasMany
    {
        return $this->hasMany(MotorMovimiento::class, 'Id_motores', 'Id_motores')
            ->orderBy('Fecha_movimiento', 'desc');
    }

    /**
     * Relación con Asignación Activa
     */
    public function asignacionActiva(): HasOne
    {
        return $this->hasOne(MotorAsignacionActiva::class, 'Id_motores', 'Id_motores')
            ->where('Estado_asignacion', 'Activa');
    }

    /**
     * Relación con todas las asignaciones
     */
    public function asignaciones(): HasMany
    {
        return $this->hasMany(MotorAsignacionActiva::class, 'Id_motores', 'Id_motores')
            ->orderBy('Fecha_salida', 'desc');
    }

    /**
     * Scope para motores disponibles
     */
    public function scopeDisponibles($query)
    {
        return $query->where('Estado', 'Disponible')
            ->where('Ubicacion_actual', 'Inventario');
    }

    /**
     * Scope para motores en reparación
     */
    public function scopeEnReparacion($query)
    {
        return $query->where('Estado', 'En Reparacion')
            ->where('Ubicacion_actual', 'Con Tecnico');
    }

    /**
     * Scope para motores en inventario
     */
    public function scopeEnInventario($query)
    {
        return $query->where('Ubicacion_actual', 'Inventario');
    }

    /**
     * Scope para motores con técnico
     */
    public function scopeConTecnico($query)
    {
        return $query->where('Ubicacion_actual', 'Con Tecnico');
    }

    /**
     * Scope para buscar por ID de motor
     */
    public function scopeBuscarPorId($query, $idMotor)
    {
        return $query->where('Id_motor', 'like', "%{$idMotor}%");
    }

    /**
     * Verificar si el motor está disponible
     */
    public function estaDisponible(): bool
    {
        return $this->Estado === 'Disponible' && $this->Ubicacion_actual === 'Inventario';
    }

    /**
     * Verificar si el motor está en reparación
     */
    public function estaEnReparacion(): bool
    {
        return $this->Estado === 'En Reparacion' && $this->Ubicacion_actual === 'Con Tecnico';
    }

    /**
     * Verificar si el motor tiene asignación activa
     */
    public function tieneAsignacionActiva(): bool
    {
        return $this->asignacionActiva()->exists();
    }

    /**
     * Obtener el último movimiento
     */
    public function ultimoMovimiento()
    {
        return $this->movimientos()->latest('Fecha_movimiento')->first();
    }

    /**
     * Contar movimientos de salida
     */
    public function contarSalidas(): int
    {
        return $this->movimientos()->where('Tipo_movimiento', 'Salida')->count();
    }

    /**
     * Contar movimientos de entrada
     */
    public function contarEntradas(): int
    {
        return $this->movimientos()->where('Tipo_movimiento', 'Entrada')->count();
    }
}