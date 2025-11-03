<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estudiante extends Model
{
    use HasFactory;

    // Configuración de la tabla y la clave primaria
    protected $table = 'estudiantes';
    protected $primaryKey = 'Id_estudiantes';
    public $incrementing = true;
    protected $keyType = 'int';

    // Habilitar o deshabilitar las marcas de tiempo (timestamps)
    public $timestamps = true;

    // Campos que pueden ser asignados masivamente
    protected $fillable = [
        'Cod_estudiante',
        'Estado',
        'Fecha_estado',
        'Id_personas',
        'Id_profesores',
        'Id_programas',
        'Id_sucursales',
        'Id_tutores',
    ];

    // --- RELACIONES ---

    public function persona()
    {
        return $this->belongsTo(Persona::class, 'Id_personas', 'Id_personas');
    }

    // Relación con el profesor
    public function profesor()
    {
        return $this->belongsTo(Profesor::class, 'Id_profesores', 'Id_profesores');
    }

    // Relación con el programa asociado
    public function programa()
    {
        return $this->belongsTo(Programa::class, 'Id_programas', 'Id_programas');
    }

    // Relación con la sucursal asociada
    public function sucursal()
    {
        return $this->belongsTo(Sucursal::class, 'Id_sucursales', 'Id_Sucursales');
    }

    // Relación con el tutor asignado
    public function tutor()
    {
        return $this->belongsTo(Tutores::class, 'Id_tutores', 'Id_tutores');
    }

    // Relación con el plan de pago del estudiante
    public function planesPago()
    {
        return $this->hasMany(PlanesPago::class, 'Id_estudiantes', 'Id_estudiantes');
    }

    // CORREGIDO: Relación con horarios (PLURAL)
    public function horarios()
    {
        return $this->hasMany(Horario::class, 'Id_estudiantes', 'Id_estudiantes');
    }

    // Relación con talleres inscritos
    public function talleresInscritos()
    {
        return $this->hasMany(EstudianteTaller::class, 'Id_estudiantes', 'Id_estudiantes');
    }

    // Relación con evaluaciones
    public function evaluaciones()
    {
        return $this->hasMany(Evaluacion::class, 'Id_estudiantes', 'Id_estudiantes');
    }

    // Relación con modelo
    public function modelo()
    {
        return $this->belongsTo(Modelo::class, 'Id_modelo', 'Id_modelo');
    }

    // --- CARGA DE RELACIONES PREDETERMINADAS ---
    protected $with = [
        'persona',
        'programa',
        'sucursal',
        'profesor',
    ];

    // --- MÉTODOS PERSONALIZADOS ---

    /**
     * Obtener el nombre completo del estudiante (nombre + apellido)
     */
    public function getFullNameAttribute()
    {
        if (!$this->persona) {
            return 'Sin nombre';
        }
        return trim(($this->persona->Nombre ?? '') . ' ' . ($this->persona->Apellido ?? ''));
    }

    /**
     * Verificar si el estudiante está activo
     */
    public function getEsActivoAttribute()
    {
        return strtolower($this->Estado ?? '') === 'activo';
    }

    /**
     * Obtener las iniciales del estudiante
     */
    public function getInicialesAttribute()
    {
        if (!$this->persona) {
            return 'SN';
        }
        $nombre = substr($this->persona->Nombre ?? 'S', 0, 1);
        $apellido = substr($this->persona->Apellido ?? 'N', 0, 1);
        return strtoupper($nombre . $apellido);
    }

    // --- EVENTOS DEL MODELO ---
    protected static function booted()
    {
        static::updating(function ($estudiante) {
            if ($estudiante->isDirty('Estado')) {
                $estudiante->Fecha_estado = now();
            }
        });
    }

    // --- MÉTODOS DE TALLERES ---

    /**
     * Método para verificar si está inscrito en un taller específico
     */
    public function estaInscritoEnTaller($idTaller)
    {
        return $this->talleresInscritos()
                    ->where('Id_programas', $idTaller)
                    ->where('Estado_inscripcion', 'inscrito')
                    ->exists();
    }

    /**
     * Obtener talleres activos del estudiante
     */
    public function talleresActivos()
    {
        return $this->talleresInscritos()
                    ->where('Estado_inscripcion', 'inscrito')
                    ->with('taller');
    }

    // --- SCOPES ---

    /**
     * Scope para obtener solo estudiantes activos
     */
    public function scopeActivos($query)
    {
        return $query->where('Estado', 'Activo');
    }

    /**
     * Scope para obtener solo estudiantes inactivos
     */
    public function scopeInactivos($query)
    {
        return $query->where('Estado', 'Inactivo');
    }

    /**
     * Scope para buscar estudiantes por nombre o código
     */
    public function scopeBuscar($query, $termino)
    {
        return $query->whereHas('persona', function($q) use ($termino) {
            $q->where('Nombre', 'like', "%{$termino}%")
              ->orWhere('Apellido', 'like', "%{$termino}%");
        })->orWhere('Cod_estudiante', 'like', "%{$termino}%");
    }
}