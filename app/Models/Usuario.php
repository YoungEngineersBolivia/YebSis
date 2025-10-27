<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\CanResetPassword;
use App\Notifications\ResetPasswordNotification; // IMPORTANTE: Agregar este import
use App\Models\Persona;
use App\Models\Tutores;
use App\Models\Profesor;

class Usuario extends Authenticatable implements CanResetPassword
{
    use Notifiable;

    protected $table = 'usuarios';
    protected $primaryKey = 'Id_usuarios';
    public $timestamps = true;
    public $incrementing = true;
    protected $keyType = 'int';

    protected $guarded = [];

    public function getAuthPassword()
    {
        return $this->Contrasenia;
    }

    public function getEmailForPasswordReset()
    {
        return $this->Correo;
    }

    public function getEmailAttribute()
    {
        return $this->attributes['Correo'] ?? null;
    }

    public function setEmailAttribute($value)
    {
        $this->attributes['Correo'] = $value;
    }

    // Método para enviar la notificación de reset
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    // ... resto de tus métodos (persona, tutor, profesor, etc.)
    
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
        return $this->hasOne(Profesor::class, 'Id_usuarios', 'Id_usuarios');
    }

    public function getRolAttribute()
    {
        if (!$this->relationLoaded('persona')) {
            $this->load('persona.rol');
        }

        if ($this->persona && $this->persona->rol) {
            return strtolower($this->persona->rol->Nombre_rol);
        }

        return null;
    }

    public function hasRole($role)
    {
        return $this->rol === strtolower($role);
    }

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