<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'usuarios';
    protected $primaryKey = 'Id_usuarios';
    public $timestamps = true;

    protected $fillable = [
        'Correo',
        'contrasenia',
        'Id_personas',
    ];

    protected $hidden = [
        'contrasenia',
    ];

    // Indica a Laravel cuál es la columna de la contraseña para Auth
    public function getAuthPassword()
    {
        return $this->contrasenia;
    }

    // Mutator: guarda hasheada al asignar contrasenia
    public function setContraseniaAttribute($value)
    {
        if ($value === null) {
            $this->attributes['contrasenia'] = null;
            return;
        }

        if (preg_match('/^\$2y\$/', $value) || preg_match('/^\$2b\$/', $value)) {
            $this->attributes['contrasenia'] = $value;
        } else {
            $this->attributes['contrasenia'] = Hash::make($value);
        }
    }
}