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
    protected $keyType = 'int';

    public function estudiantes()
    {
        return $this->hasMany(Estudiante::class, 'Id_personas', 'Id_personas');
    }
}
