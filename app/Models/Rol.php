<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
    protected $table = 'Rol';
    protected $primaryKey = 'Id_Rol';

    protected $fillable = ['Nombre_rol'];

    public $timestamps = true; // solo si usas created_at / updated_at
}
