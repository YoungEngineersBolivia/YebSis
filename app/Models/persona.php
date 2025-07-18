<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Persona extends Model
{
    use HasFactory;
    use Notifiable;
    protected $fillable = [
        'nombre',
        'apellido',
        'genero',
        'fecha_nacimiento',
        'fecha_registro',
        'celular',
        'direccion_domicilio',
    ];

    protected $hidden = [
    ];
    protected function casts(): array
    {
        return [
            'fecha_nacimiento' => 'datetime',
        ];
    }
    protected $table = 'Personas'; 
    protected $primaryKey = 'id_Persona';
    public $timestamps = true;
}
