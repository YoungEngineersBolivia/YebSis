<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Profesor extends Model
{
    use HasFactory;

    protected $table = 'profesores';
    protected $primaryKey = 'Id_profesores';
    public $incrementing = true;
    protected $keyType = 'int';

    // Si tu tabla NO tiene created_at / updated_at, cambia a false o mapea los nombres:
    public $timestamps = true;
    // public const CREATED_AT = 'Fecha_creacion';
    // public const UPDATED_AT = 'Fecha_actualizacion';

    protected $fillable = [
        'Profesion',
        'Id_personas',
        'Id_usuarios',
        'Rol_componentes',
    ];

    // Cargar persona por defecto (opcional, útil para la vista)
    protected $with = ['persona'];

    public function persona()
    {
        return $this->belongsTo(Persona::class, 'Id_personas', 'Id_personas');
    }

    public function horarios()
    {
        return $this->hasMany(Horario::class, 'Id_profesores', 'Id_profesores');
    }
    
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'Id_usuarios', 'Id_usuarios');
    }

    // <<< IMPORTANTE >>> relación inversa: un profesor tiene muchos estudiantes
    public function estudiantes()
    {
        return $this->hasMany(Estudiante::class, 'Id_profesores', 'Id_profesores');
    }

    // (Opcional) Accesor para nombre completo del profesor (vía Persona)
    protected $appends = ['nombre_completo'];

    public function getNombreCompletoAttribute()
    {
        $p = $this->persona;
        return $p ? trim(($p->Nombre ?? '') . ' ' . ($p->Apellido ?? '')) : null;
    }
}
