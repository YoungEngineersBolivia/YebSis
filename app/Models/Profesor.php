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
    public $timestamps = true;

    protected $fillable = [
        'Profesion',
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
}
