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

    // --- RELACIONES QUE NECESITA LA VISTA ---
    // Relación con la persona asociada al estudiante
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
    public function planPago()
    {
        return $this->hasOne(PlanesPago::class, 'Id_estudiantes', 'Id_estudiantes');
    }

    // --- CARGA DE RELACIONES ---

    // Este método no debería incluir relaciones anidadas como 'profesor.persona' en $with.
    // Asegúrate de cargarlas solo cuando sea necesario, usando 'with()' en los métodos del controlador.

    // Puedes utilizar el siguiente atributo para cargar las relaciones necesarias de manera predeterminada:
    protected $with = [
        'persona',
        'programa',
        'sucursal',
        'profesor',
    ];

    // --- OTROS MÉTODOS PERSONALIZADOS ---

    /**
     * Obtener el nombre completo del estudiante (nombre + apellido)
     */
    public function getFullNameAttribute()
    {
        return $this->persona->Nombre . ' ' . $this->persona->Apellido;
    }

    // app/Models/Estudiante.php
    protected static function booted()
    {
        static::updating(function ($est) {
            if ($est->isDirty('Estado')) {
                $est->Fecha_estado = now(); // actualiza la fecha al cambiar el estado
            }
        });
    }

}
