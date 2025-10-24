<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Usuario extends Authenticatable
{
    use Notifiable;

    // Mapea la tabla 'usuarios' creada en tu migration
    protected $table = 'usuarios';
    protected $primaryKey = 'Id_usuarios';
    public $timestamps = true;
    public $incrementing = true;
    protected $keyType = 'int';

    // Nombre del campo que contiene la contraseña en tu tabla
    public function getAuthPassword()
    {
        return $this->Contrasenia;
    }

    // Opcional: evita que se asigne masivamente campos no deseados
    protected $guarded = [];

    // Relación con persona
    public function persona()
    {
        return $this->belongsTo(Persona::class, 'Id_personas', 'Id_personas');
    }

    public function tutor()
    {
        return $this->hasOne(Tutores::class, 'Id_usuarios');
    }

    public function profesor()
    {
        return $this->hasOne(\App\Models\Profesor::class, 'Id_usuarios', 'Id_usuarios');
    }

    /**
     * Accessor para obtener el rol del usuario
     * Obtiene el nombre del rol desde: usuario -> persona -> role
     */
    public function getRolAttribute()
    {
        // Si no está cargada la relación, cargarla
        if (!$this->relationLoaded('persona')) {
            $this->load('persona.rol');
        }

        // Retornar el nombre del rol en minúsculas
        if ($this->persona && $this->persona->rol) {
            return strtolower($this->persona->rol->Nombre_rol);
        }

        return null;
    }

    /**
     * Verificar si el usuario tiene un rol específico
     */
    public function hasRole($role)
    {
        return $this->rol === strtolower($role);
    }

    /**
     * Verificar si el usuario tiene alguno de los roles dados
     */
    public function hasAnyRole($roles)
    {
        $roles = is_array($roles) ? $roles : func_get_args();
        $userRole = $this->rol;
        
        foreach ($roles as $role) {
            if ($userRole === strtolower($role)) {
                return true;
            }
        }
        
        return false;
    }
}