<?php
// App\Models\MotorAsignado.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MotorAsignado extends Model
{
    use HasFactory;

    protected $table = 'motores_asignados';
    protected $primaryKey = 'Id_motores_asignados';

    protected $fillable = [
        'Id_motores',
        'Id_profesores',
        'Estado_asignacion',
        'Fecha_asignacion',
        'Fecha_entrega',
        'Observacion_inicial',
    ];

    public function motor()
    {
        return $this->belongsTo(Motor::class, 'Id_motores', 'Id_motores');
    }

    public function profesor()
    {
        return $this->belongsTo(Profesor::class, 'Id_profesores', 'Id_profesores');
    }

    public function reportes()
    {
        return $this->hasMany(ReporteMantenimiento::class, 'Id_motores_asignados', 'Id_motores_asignados');
    }
}