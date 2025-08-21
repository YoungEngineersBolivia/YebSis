<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cuota extends Model
{
    protected $table = 'cuotas';
    protected $primaryKey = 'Id_cuotas';
    public $timestamps = false;

    protected $fillable = [
        'Nro_de_cuota',
        'Fecha_vencimiento',
        'Monto_cuota',
        'Monto_pagado',
        'Estado_cuota',
        'Id_planes_pagos'
    ];

    public function planPago()
    {
        return $this->belongsTo(\App\Models\PlanesPago::class, 'Id_planes_pagos', 'Id_planes_pagos');
    }
}
