<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    protected $table = 'pagos';
    protected $primaryKey = 'Id_pagos';
    public $timestamps = true;

    protected $fillable = [
        'Descripcion',
        'Comprobante',
        'Monto_pago',
        'Fecha_pago',
        'Id_planes_pagos',
    ];

    protected $casts = [
        'Fecha_pago' => 'date',
        'Monto_pago' => 'decimal:2',
    ];

    public function planPago()
    {
        return $this->belongsTo(PlanesPago::class, 'Id_planes_pagos', 'Id_planes_pagos');
    }
}
