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

    // Cambia a false si tu tabla NO tiene created_at / updated_at
    public $timestamps = true;

    protected $fillable = [
        'Cod_estudiante',
        'Estado',
        'Id_personas',
        'Id_profesores',
        'Id_programas',
        'Id_sucursales',
        'Id_tutores',
    ];

    // --- RELACIONES QUE NECESITA LA VISTA ---

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
        // FK en estudiantes:  Id_programas
        // PK en programas:    Id_programas
        return $this->belongsTo(Programa::class, 'Id_programas', 'Id_programas');
    }

    public function sucursal()
    {
        // FK en estudiantes:  Id_sucursales
        // PK en sucursales:   Id_Sucursales
        return $this->belongsTo(Sucursal::class, 'Id_sucursales', 'Id_Sucursales');
    }

    public function tutor()
    {
        return $this->belongsTo(Tutores::class, 'Id_tutores', 'Id_tutores');
    }

    public function planPago()
    {
        return $this->hasOne(PlanesPago::class, 'Id_estudiantes', 'Id_estudiantes');
    }

    protected $with = [
        'persona',
        'programa',
        'sucursal',
        'profesor.persona', 
    ];
}
