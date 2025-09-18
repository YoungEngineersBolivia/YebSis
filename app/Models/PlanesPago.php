<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlanesPago extends Model
{
    protected $table = 'planes_pagos';
    protected $primaryKey = 'Id_planes_pagos';
    public $timestamps = true;

    protected $fillable = [
        'Monto_total',
        'Nro_cuotas',
        'fecha_plan_pagos',
        'Estado_plan',
        'Id_programas',
        'Id_pagos',
        'Id_estudiantes',
    ];

    public function cuotas()
    {
        return $this->hasMany(Cuota::class, 'Id_planes_pagos', 'Id_planes_pagos');
    }

    public function programa()
    {
        return $this->belongsTo(Programa::class, 'Id_programas', 'Id_programas');
    }

    public function estudiante()
    {
        return $this->belongsTo(Estudiante::class, 'Id_estudiantes', 'Id_estudiantes');
    }
}
