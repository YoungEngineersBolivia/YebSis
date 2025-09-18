<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Programa extends Model
{
    use HasFactory;

    protected $table = 'programas'; 
    protected $primaryKey = 'Id_programas';
    
    protected $fillable = [
        'Nombre',
        'Costo',
        'Rango_edad',
        'Duracion',
        'Descripcion',
        'Foto',
        'Tipo'
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
    
    public function inscripcionesTalleres()
    {
        return $this->hasMany(EstudianteTaller::class, 'Id_programas', 'Id_programas');
    }

    // Scope para obtener solo talleres
    public function scopeTalleres($query)
    {
        return $query->whereIn('Tipo', ['taller_invierno', 'taller_verano']);
    }

    // Scope para obtener solo programas regulares
    public function scopeProgramasRegulares($query)
    {
        return $query->where('Tipo', 'programa');
    }

    // Scope para talleres activos
    public function scopeActivos($query)
    {
        return $query->where('Estado', true);
    }

    public function planesPago()
    {
        return $this->hasMany(PlanesPago::class, 'Id_programas', 'Id_programas');
    }

    public function estudiantesTalleres()
    {
        return $this->hasMany(EstudianteTaller::class, 'Id_programas', 'Id_programas');
    }
}