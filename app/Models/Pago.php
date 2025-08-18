<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    protected $table = 'pagos';

    protected $fillable = [
        'Descripcion',
        'Comprobante',
        'Monto_pago',
        'Fecha_pago',
        'Id_planes_pagos',
        'Id_cuotas'
    ];
}
