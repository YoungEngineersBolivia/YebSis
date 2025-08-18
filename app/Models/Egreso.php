<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Egreso extends Model
{
    use HasFactory;

    protected $table = 'egresos';
    public $timestamps = false; // Add this line to disable timestamps
    
    protected $fillable = [
        'Tipo',
        'Descripcion_egreso',
        'Fecha_egreso',
        'Monto_egreso'
    ];
}
