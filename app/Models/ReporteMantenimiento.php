<?php
// App\Models\ReporteMantenimiento.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReporteMantenimiento extends Model
{
    use HasFactory;

    protected $table = 'reportes_mantenimiento';
    protected $primaryKey = 'Id_reportes';

    protected $fillable = [
        'Id_motores_asignados',
        'Estado_final',
        'Observaciones',
        'Fecha_reporte',
    ];

    public function motorAsignado()
    {
        return $this->belongsTo(MotorAsignado::class, 'Id_motores_asignados', 'Id_motores_asignados');
    }
}