<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Programa extends Model
{
    use HasFactory;

    protected $table = 'programas'; 

    protected $fillable = [
        'Nombre',
        'Costo',
        'Rango_edad',
        'Duracion',
        'Descripcion',
        'Foto'
    ];

    protected $casts = [
        'Costo' => 'decimal:2',
    ];

    // Accessor para la URL completa de la foto
    public function getFotoUrlAttribute()
    {
        if ($this->Foto) {
            return asset('storage/' . $this->Foto);
        }
        return asset('images/default-program.png'); // Imagen por defecto
    }

    // Scope para buscar programas por nombre
    public function scopeBuscarPorNombre($query, $nombre)
    {
        return $query->where('Nombre', 'LIKE', '%' . $nombre . '%');
    }

    // Scope para filtrar por rango de precio
    public function scopeFiltroPorPrecio($query, $min = null, $max = null)
    {
        if ($min) {
            $query->where('Costo', '>=', $min);
        }
        if ($max) {
            $query->where('Costo', '<=', $max);
        }
        return $query;
    }

}