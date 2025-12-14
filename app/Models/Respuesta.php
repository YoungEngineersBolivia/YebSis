<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Respuesta extends Model
{
    use HasFactory;

    protected $table = 'respuestas';
    protected $primaryKey = 'Id_respuestas';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = true;

    protected $fillable = [
        'Respuesta',
    ];

    // Constantes para las respuestas predefinidas
    const SI = 1;
    const NO = 2;
    const EN_PROCESO = 3;

    // --- RELACIONES ---

    /**
     * Relación con Evaluaciones
     */
    public function evaluaciones()
    {
        return $this->hasMany(Evaluacion::class, 'Id_respuestas', 'Id_respuestas');
    }

    // --- MÉTODOS ESTÁTICOS ---

    /**
     * Obtener el ID de la respuesta "Sí"
     */
    public static function getSiId()
    {
        return self::SI;
    }

    /**
     * Obtener el ID de la respuesta "No"
     */
    public static function getNoId()
    {
        return self::NO;
    }

    /**
     * Obtener el ID de la respuesta "En proceso"
     */
    public static function getEnProcesoId()
    {
        return self::EN_PROCESO;
    }

    /**
     * Obtener todas las respuestas como opciones para select
     */
    public static function getOpciones()
    {
        return self::all()->pluck('Respuesta', 'Id_respuestas');
    }
}
