<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estudiante extends Model
{
    use HasFactory;

    protected $table = 'estudiantes';
    protected $primaryKey = 'Id_estudiantes';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = true;

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

    public function profesor()
    {
        return $this->belongsTo(Profesor::class, 'Id_profesores', 'Id_profesores');
    }

    public function programa()
    {
        return $this->belongsTo(Programa::class, 'Id_programas', 'Id_programas');
    }

    public function sucursal()
    {
        return $this->belongsTo(Sucursal::class, 'Id_sucursales', 'Id_sucursales');
    }

    public function tutor()
    {
        return $this->belongsTo(Tutores::class, 'Id_tutores', 'Id_tutores');
    }

    public function planesPago()
    {
        return $this->hasMany(PlanesPago::class, 'Id_estudiantes', 'Id_estudiantes');
    }

    public function horarios()
    {
        return $this->hasMany(Horario::class, 'Id_estudiantes', 'Id_estudiantes');
    }

    public function talleresInscritos()
    {
        return $this->hasMany(EstudianteTaller::class, 'Id_estudiantes', 'Id_estudiantes');
    }

    public function evaluaciones()
    {
        return $this->hasMany(Evaluacion::class, 'Id_estudiantes', 'Id_estudiantes');
    }

    public function modelo()
    {
        return $this->belongsTo(Modelo::class, 'Id_modelo', 'Id_modelo');
    }

    public function asistencias()
    {
        return $this->hasMany(Asistencia::class, 'Id_estudiantes', 'Id_estudiantes');
    }

    // --- RELACIONES POR DEFECTO (Eager Loading) ---
    protected $with = [
        'persona',
        'programa',
        'sucursal',
        'profesor',
    ];

    // --- ACCESORES ---
    public function getFullNameAttribute()
    {
        return $this->persona ? trim(($this->persona->Nombre ?? '') . ' ' . ($this->persona->Apellido ?? '')) : 'Sin nombre';
    }

    public function getEsActivoAttribute()
    {
        return strtolower($this->Estado ?? '') === 'activo';
    }

    public function getInicialesAttribute()
    {
        if (!$this->persona) return 'SN';
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

    // --- MÉTODOS PERSONALIZADOS ---
    public function estaInscritoEnTaller($idTaller)
    {
        return $this->talleresInscritos()
                    ->where('Id_programas', $idTaller)
                    ->where('Estado_inscripcion', 'inscrito')
                    ->exists();
    }

    public function talleresActivos()
    {
        return $this->talleresInscritos()
                    ->where('Estado_inscripcion', 'inscrito')
                    ->with('taller');
    }

    // --- SCOPES ---
    public function scopeActivos($query)
    {
        return $query->where('Estado', 'Activo');
    }

    public function scopeInactivos($query)
    {
        return $query->where('Estado', 'Inactivo');
    }

    public function scopeBuscar($query, $termino)
    {
        return $query->whereHas('persona', function($q) use ($termino) {
            $q->where('Nombre', 'like', "%{$termino}%")
              ->orWhere('Apellido', 'like', "%{$termino}%");
        })->orWhere('Cod_estudiante', 'like', "%{$termino}%");
    }
}
