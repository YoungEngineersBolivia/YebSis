<?php
// App\Models\Motor.php

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

    public function sucursal()
    {
        return $this->belongsTo(Sucursal::class, 'Id_sucursales', 'Id_Sucursales');
    }

    public function movimientos()
    {
        return $this->hasMany(MotorMovimiento::class, 'Id_motores', 'Id_motores');
    }

    public function asignacionActiva()
    {
        return $this->hasOne(MotorAsignado::class, 'Id_motores', 'Id_motores')
            ->where('Estado_asignacion', 'En Proceso')
            ->latest('Fecha_asignacion');
    }

    public function ultimoMovimiento()
    {
        return $this->hasOne(MotorMovimiento::class, 'Id_motores', 'Id_motores')
                    ->latest('Fecha');
    }

    public function asignaciones()
    {
        return $this->hasMany(MotorAsignado::class, 'Id_motores', 'Id_motores');
    }
}