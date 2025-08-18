<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Publicacion extends Model
{
    use HasFactory;

    protected $table = 'publicaciones';

    protected $primaryKey = 'Id_publicaciones';

    protected $fillable = [
        'Nombre',
        'Descripcion',
        'Imagen',
        'Fecha',
        'Hora',
        'Estado',
        'Id_personas',
    ];

    public $timestamps = true;
}
