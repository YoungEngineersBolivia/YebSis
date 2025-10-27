<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    use HasFactory;

    protected $table = 'personas';
    protected $primaryKey = 'Id_personas';
    public $incrementing = true;

    protected $fillable = [
        'Nombre',
        'Apellido',
        'Genero',
        'Direccion_domicilio',
        'Fecha_nacimiento',
        'Fecha_registro',
        'Celular',
        'Id_roles',
    ];

    protected $casts = [
        'Fecha_nacimiento' => 'date',
        'Fecha_registro' => 'date',
    ];

    /**
     * Relación con la tabla roles
     * IMPORTANTE: Esta relación es clave para obtener el rol del usuario
     */
    public function rol()
    {
        return $this->belongsTo(Rol::class, 'Id_roles', 'Id_roles');
    }

    /**
     * Relación con usuario
     */
    public function usuario()
    {
        return $this->hasOne(Usuario::class, 'Id_personas', 'Id_personas');
    }

    /**
     * Relación con profesor
     */
    public function profesor()
    {
        return $this->hasOne(Profesor::class, 'Id_personas', 'Id_personas');
    }

    /**
     * Relación con tutor
     */
    public function tutor()
    {
        return $this->hasOne(Tutores::class, 'Id_personas', 'Id_personas');
    }

    /**
     * Relación con estudiante
     */
    public function estudiante()
    {
        return $this->hasOne(Estudiante::class, 'Id_personas', 'Id_personas');
    }

    /**
     * Obtener el nombre completo
     */
    public function getNombreCompletoAttribute()
    {
        return trim($this->Nombre . ' ' . $this->Apellido);
    }
}