<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Egreso extends Model
{
    use HasFactory;

    protected $table = 'egresos';
    protected $primaryKey = 'Id_egreso';
    public $timestamps = true; 

    protected $fillable = [
        'Tipo',
        'Descripcion_egreso',
        'Fecha_egreso',
        'Monto_egreso'
    ];
}
