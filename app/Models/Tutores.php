<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tutores extends Model  
{
    use HasFactory;

    protected $table = 'tutores';
    protected $primaryKey = 'Id_tutores';
    public $timestamps = true;

    protected $fillable = [
        'Descuento',
        'Parentesco', 
        'Nit',
        'Nombre_factura',
        'Id_personas',
        'Id_usuarios',
    ];

    public function persona()
    {
        return $this->belongsTo(Persona::class, 'Id_personas', 'Id_personas');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'Id_usuarios', 'Id_usuarios');
    }

    public function estudiantes()
    {
        return $this->hasMany(Estudiante::class, 'Id_tutores', 'Id_tutores');
    }
}