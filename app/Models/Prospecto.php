<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prospecto extends Model
{
    use HasFactory;

    // Nombre de la tabla
    protected $table = 'prospectos';

    // Llave primaria
    protected $primaryKey = 'Id_prospectos';

    // Campos que se pueden llenar masivamente
    protected $fillable = [
        'Nombre',
        'Apellido',
        'Celular',
        'Estado_prospecto',
        'Id_roles'
    ];

    public $incrementing = True;

    // Si quieres, puedes desactivar timestamps si no los usas
    // public $timestamps = false;
}
