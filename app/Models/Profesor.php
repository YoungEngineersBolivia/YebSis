<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Profesor extends Model
{
    use HasFactory;

    protected $table = 'profesores';
    protected $primaryKey = 'Id_profesores';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = true;

    protected $fillable = [
        'Profesion',
        'Id_personas',
        'Id_usuarios',
        'Rol_componentes',
    ];

    /**
     * Relación con Persona
     */
    public function persona(): BelongsTo
    {
        return $this->belongsTo(Persona::class, 'Id_personas', 'Id_personas');
    }

    /**
     * Relación con Usuario
     * CORREGIDO: Usa Id_usuarios como clave primaria de usuarios
     */
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'Id_usuarios', 'Id_usuarios');
    }

    /**
     * Relación con Horarios
     */
    public function horarios(): HasMany
    {
        return $this->hasMany(Horario::class, 'Id_profesores', 'Id_profesores');
    }

    /**
     * Relación con Estudiantes
     */
    public function estudiantes(): HasMany
    {
        return $this->hasMany(Estudiante::class, 'Id_profesores', 'Id_profesores');
    }

    /**
     * Relación con Motores asignados (como técnico)
     */
    public function motoresAsignados(): HasMany
    {
        return $this->hasMany(Motor::class, 'Id_tecnico_actual', 'Id_profesores');
    }

    /**
     * Relación con Asignaciones Activas
     */
    public function asignacionesActivas(): HasMany
    {
        return $this->hasMany(MotorAsignacionActiva::class, 'Id_profesores', 'Id_profesores')
            ->whereIn('Estado_asignacion', ['Activa', 'Pendiente Entrada']);
    }

    /**
     * Relación con todas las asignaciones
     */
    public function asignaciones(): HasMany
    {
        return $this->hasMany(MotorAsignacionActiva::class, 'Id_profesores', 'Id_profesores');
    }

    /**
     * Relación con Movimientos de motores
     */
    public function movimientos(): HasMany
    {
        return $this->hasMany(MotorMovimiento::class, 'Id_profesores', 'Id_profesores');
    }

    /**
     * Scope para técnicos
     */
    public function scopeTecnicos($query)
    {
        return $query->where('Rol_componentes', 'Tecnico');
    }

    /**
     * Scope para personal de inventario
     */
    public function scopeInventario($query)
    {
        return $query->where('Rol_componentes', 'Inventario');
    }

    /**
     * Relación con Clases de Prueba
     */
    public function clasesPrueba()
    {
        return $this->hasMany(ClasePrueba::class, 'Id_profesores', 'Id_profesores');
    }

    /**
     * Accesor para nombre completo
     */
    public function getNombreCompletoAttribute()
    {
        $p = $this->persona;
        return $p ? trim(($p->Nombre ?? '') . ' ' . ($p->Apellido ?? '')) : null;
    }
}