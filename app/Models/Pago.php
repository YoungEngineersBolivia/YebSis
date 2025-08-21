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
    ];
}
