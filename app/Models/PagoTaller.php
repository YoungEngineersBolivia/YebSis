<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PagoTaller extends Model
{
    use HasFactory;

    protected $table = 'pagos_talleres';
    protected $primaryKey = 'Id_pagos_talleres';

    protected $fillable = [
        'Descripcion',
        'Comprobante',
        'Monto_pago',
        'Fecha_pago',
        'Estado_pago',
        'Metodo_pago',
        'Id_estudiantes_talleres'
    ];

    protected $casts = [
        'Fecha_pago' => 'date',
        'Monto_pago' => 'decimal:2'
    ];

    // Relación con EstudianteTaller
    public function estudianteTaller()
    {
        return $this->belongsTo(EstudianteTaller::class, 'Id_estudiantes_talleres', 'Id_estudiantes_talleres');
    }

    // Obtener el estudiante a través de la inscripción
    public function estudiante()
    {
        return $this->hasOneThrough(
            Estudiante::class,
            EstudianteTaller::class,
            'Id_estudiantes_talleres', // Foreign key en estudiantes_talleres
            'Id_estudiantes', // Foreign key en estudiantes
            'Id_estudiantes_talleres', // Local key en pagos_talleres
            'Id_estudiantes' // Local key en estudiantes_talleres
        );
    }

    // Obtener el taller a través de la inscripción
    public function taller()
    {
        return $this->hasOneThrough(
            Programa::class,
            EstudianteTaller::class,
            'Id_estudiantes_talleres', // Foreign key en estudiantes_talleres
            'Id_programas', // Foreign key en programas
            'Id_estudiantes_talleres', // Local key en pagos_talleres
            'Id_programas' // Local key en estudiantes_talleres
        );
    }

    // Scopes útiles
    public function scopePagados($query)
    {
        return $query->where('Estado_pago', 'pagado');
    }

    public function scopePendientes($query)
    {
        return $query->where('Estado_pago', 'pendiente');
    }

    public function scopeVencidos($query)
    {
        return $query->where('Estado_pago', 'vencido');
    }

    public function scopeDelMes($query, $mes = null, $año = null)
    {
        $mes = $mes ?? now()->month;
        $año = $año ?? now()->year;
        
        return $query->whereMonth('Fecha_pago', $mes)
                    ->whereYear('Fecha_pago', $año);
    }
}