<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pregunta extends Model
{
    use HasFactory;

    protected $table = 'preguntas';
    protected $primaryKey = 'Id_preguntas';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = true;

    protected $fillable = [
        'Pregunta',
        'Id_programas',
    ];

    // --- RELACIONES ---

    /**
     * Relación con Programa
     */
    public function programa()
    {
        return $this->belongsTo(Programa::class, 'Id_programas', 'Id_programas');
    }

    /**
     * Relación con Evaluaciones
     */
    public function evaluaciones()
    {
        return $this->hasMany(Evaluacion::class, 'Id_preguntas', 'Id_preguntas');
    }

    // --- SCOPES ---

    /**
     * Scope para filtrar preguntas por programa
     */
    public function scopePorPrograma($query, $idPrograma)
    {
        return $query->where('Id_programas', $idPrograma);
    }

    /**
     * Scope para ordenar por fecha de creación
     */
    public function scopeOrdenadas($query)
    {
        return $query->orderBy('created_at', 'asc');
    }
}
