<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

class Egresos extends Model
{
    use HasFactory;
    protected $table = 'egresos';
    protected $primaryKey = 'Id_egreso';

    public $incrementing = true;
    protected $keyType = 'int';
    
    public $timestamps = true;

    protected $fillable = [
        'Tipo',
        'Descripcion_egresos',
        'Fecha_egreso',
        'Monto_egreso'
    ];

    public function egresos()
    {
        return $this->hasMany(Egreso::class,'Id_egresos');
    }
}
