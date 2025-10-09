<?php

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

    protected $casts = [
        'Fecha' => 'date',
    ];

    // Relación con Motor
    public function motor()
    {
        return $this->belongsTo(Motor::class, 'Id_motores', 'Id_motores');
    }

    // Relación con Sucursal
    public function sucursal()
    {
        return $this->belongsTo(Sucursal::class, 'Id_sucursales', 'Id_Sucursales');
    }

    // Relación con Usuario
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'Id_usuarios', 'Id_usuarios');
    }
}