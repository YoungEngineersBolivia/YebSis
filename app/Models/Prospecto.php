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
    // public $timestamps = false;

    /**
     * Relación con Clases de Prueba
     */
    public function clasesPrueba()
    {
        return $this->hasMany(ClasePrueba::class, 'Id_prospectos', 'Id_prospectos');
    }
}
