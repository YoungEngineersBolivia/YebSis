<?php
// App\Models\MotorMovimiento.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MotorMovimiento extends Model
{
    use HasFactory;

    protected $table = 'motores_movimientos';
    protected $primaryKey = 'Id_movimientos';

    protected $fillable = [
        'Id_motores',
        'Tipo_movimiento',
        'Fecha',
        'Id_sucursales',
        'Estado_ubicacion',
        'Ultimo_tecnico',
        'Observacion',
        'Id_usuarios',
    ];

    public function motor()
    {
        return $this->belongsTo(Motor::class, 'Id_motores', 'Id_motores');
    }

    public function sucursal()
    {
        return $this->belongsTo(Sucursal::class, 'Id_sucursales', 'Id_Sucursales');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'Id_usuarios', 'Id_usuarios');
    }
}