<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notificacion extends Model
{
    use HasFactory;

    protected $table = 'notificaciones';

    protected $primaryKey = 'Id_notificaciones';

    protected $fillable = [
        'Nombre',
        'Descripcion',
        'Imagen',
        'Fecha',
        'Hora',
        'Estado',
        'Id_tutores',
    ];

    public $timestamps = true;
}
