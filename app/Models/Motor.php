<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Motor extends Model
{
    use HasFactory;

    protected $table = 'motores';
    protected $primaryKey = 'Id_motores';

    protected $fillable = [
        'Id_motor',
        'Estado',
        'Observacion',
        'Id_sucursales',
    ];

    // Relación con Sucursal
    public function sucursal()
    {
        return $this->belongsTo(Sucursal::class, 'Id_sucursales', 'Id_Sucursales');
    }

    // Relación con todos los movimientos
    public function movimientos()
    {
        return $this->hasMany(MotorMovimiento::class, 'Id_motores', 'Id_motores')
            ->orderBy('Fecha', 'desc');
    }

    // Relación con el último movimiento
    public function ultimoMovimiento()
    {
        return $this->hasOne(MotorMovimiento::class, 'Id_motores', 'Id_motores')
            ->latestOfMany('Fecha');
    }

    // Relación con motores asignados
    public function motoresAsignados()
    {
        return $this->hasMany(MotorAsignado::class, 'Id_motores', 'Id_motores');
    }
}