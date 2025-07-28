<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
    protected $table = 'roles';
    protected $primaryKey = 'Id_roles';
    public $timestamps = true;

    protected $fillable = ['Nombre_rol'];
}
