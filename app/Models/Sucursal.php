<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sucursal extends Model
{
    use HasFactory;

    protected $table = 'sucursales';

    protected $primaryKey = 'Id_sucursales';

    public $incrementing = true;
    protected $keyType = 'int';

    public $timestamps = true;

    protected $fillable = [
        'Nombre',
        'Direccion',
    ];

    public function estudiantes()
    {
        return $this->hasMany(Estudiante::class, 'Id_sucursales', 'Id_sucursales');
    }
}
