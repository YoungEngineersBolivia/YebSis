<?php

namespace App\Http\Controllers;

use App\Models\Motor;
use App\Models\Sucursal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfesorInventarioController extends Controller
{
    /**
     * Mostrar el inventario de motores para profesores
     */
    public function inventarioMotores()
    {
        // Obtener todos los motores con su sucursal
        $motores = Motor::with(['sucursal', 'ultimoMovimiento'])
            ->orderBy('Id_motor', 'asc')
            ->get();

        // Calcular estadísticas
        $stats = [
            'total' => $motores->count(),
            'funcionando' => $motores->where('Estado', 'Funcionando')->count(),
            'descompuesto' => $motores->where('Estado', 'Descompuesto')->count(),
            'en_proceso' => $motores->where('Estado', 'En Proceso')->count(),
        ];

        return view('profesor.inventarioMotores', compact('motores', 'stats'));
    }

    /**
     * Obtener motores filtrados (AJAX)
     */
    public function filtrarMotores(Request $request)
    {
        $query = Motor::with('sucursal');

        // Filtrar por estado
        if ($request->has('estado') && $request->estado != 'todos') {
            $query->where('Estado', $request->estado);
        }

        // Filtrar por búsqueda
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('Id_motor', 'like', "%{$search}%")
                  ->orWhere('Observacion', 'like', "%{$search}%")
                  ->orWhereHas('sucursal', function($sq) use ($search) {
                      $sq->where('Nombre', 'like', "%{$search}%");
                  });
            });
        }

        $motores = $query->orderBy('Id_motor', 'asc')->get();

        return response()->json([
            'success' => true,
            'motores' => $motores->map(function($motor) {
                return [
                    'id_motor' => $motor->Id_motor,
                    'estado' => $motor->Estado,
                    'sucursal' => $motor->sucursal ? $motor->sucursal->Nombre : 'Sin sucursal',
                    'observacion' => $motor->Observacion,
                    'ultimo_movimiento' => $motor->ultimoMovimiento 
                        ? $motor->ultimoMovimiento->Fecha 
                        : $motor->updated_at->format('Y-m-d')
                ];
            })
        ]);
    }

    /**
     * Ver detalle de un motor
     */
    public function detalleMotor($id)
    {
        $motor = Motor::with(['sucursal', 'movimientos.usuario'])
            ->findOrFail($id);

        return view('profesor.detalleMotor', compact('motor'));
    }
}