<?php

namespace App\Http\Controllers;

use App\Models\Egreso;
use Illuminate\Http\Request;

class EgresosController extends Controller
{
    public function index(Request $request)
    {
        $mesSeleccionado = $request->input('mes', \Carbon\Carbon::now()->month);
        $anioSeleccionado = $request->input('anio', \Carbon\Carbon::now()->year);

        $egresos = Egreso::whereMonth('Fecha_egreso', (int) $mesSeleccionado)
            ->whereYear('Fecha_egreso', (int) $anioSeleccionado)
            ->orderBy('Fecha_egreso', 'desc')
            ->get();
        $totalMes = $egresos->sum('Monto_egreso');

        // Resumen mensual para el año seleccionado (Unificación)
        $resumenMensual = Egreso::select(
            \DB::raw('MONTH(Fecha_egreso) as mes'),
            \DB::raw('SUM(Monto_egreso) as total'),
            \DB::raw('COUNT(*) as cantidad')
        )
            ->whereYear('Fecha_egreso', (int) $anioSeleccionado)
            ->groupBy('mes')
            ->orderBy('mes', 'desc')
            ->get()
            ->map(function ($item) {
                $item->nombre_mes = \Carbon\Carbon::create()->month($item->mes)->monthName;
                return $item;
            });

        $estadisticasTiempo = [
            'mes_num' => (int) $mesSeleccionado,
            'anio_actual' => (int) $anioSeleccionado,
            'mes_nombre' => \Carbon\Carbon::create()->month((int) $mesSeleccionado)->monthName
        ];

        // Obtener el año más antiguo registrado para el dropdown dinámico
        $anioMinimo = Egreso::min(\DB::raw('YEAR(Fecha_egreso)')) ?? \Carbon\Carbon::now()->year;

        return view('administrador.egresosAdministrador', compact('egresos', 'totalMes', 'estadisticasTiempo', 'resumenMensual', 'anioMinimo'));
    }

    public function reporteMensual(Request $request)
    {
        $anioSeleccionado = $request->input('anio', \Carbon\Carbon::now()->year);

        $query = Egreso::select(
            \DB::raw('YEAR(Fecha_egreso) as anio'),
            \DB::raw('MONTH(Fecha_egreso) as mes'),
            \DB::raw('SUM(Monto_egreso) as total'),
            \DB::raw('COUNT(*) as cantidad_egresos'),
            \DB::raw('MAX(Fecha_egreso) as ultima_fecha')
        );

        $query->whereYear('Fecha_egreso', $anioSeleccionado);

        $egresosMensuales = $query->groupBy('anio', 'mes')
            ->orderBy('anio', 'desc')
            ->orderBy('mes', 'desc')
            ->get()
            ->map(function ($item) {
                $item->nombre_mes = \Carbon\Carbon::create()->month($item->mes)->monthName;
                return $item;
            });

        // Obtener el año más antiguo registrado para el dropdown dinámico
        $anioMinimo = Egreso::min(\DB::raw('YEAR(Fecha_egreso)')) ?? \Carbon\Carbon::now()->year;

        return view('administrador.egresosMensuales', compact('egresosMensuales', 'anioSeleccionado', 'anioMinimo'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'Tipo' => 'required|string|max:255',
            'Descripcion_egreso' => 'required|string',
            'Fecha_egreso' => 'required|date',
            'Monto_egreso' => 'required|numeric|min:0',
        ]);

        Egreso::create($request->all());

        return redirect()->route('egresos.index')
            ->with('success', 'Egreso registrado exitosamente');
    }

    // Método para editar
    public function update(Request $request, $id_egreso)
    {
        $request->validate([
            'Tipo' => 'required|string|max:255',
            'Descripcion_egreso' => 'required|string',
            'Fecha_egreso' => 'required|date',
            'Monto_egreso' => 'required|numeric|min:0',
        ]);

        $egreso = Egreso::findOrFail($id_egreso);
        $egreso->update($request->all());

        return redirect()->route('egresos.index')
            ->with('success', 'Egreso actualizado exitosamente');
    }

    // Método para eliminar
    public function destroy($id_egreso)
    {
        $egreso = Egreso::findOrFail($id_egreso);
        $egreso->delete();

        return redirect()->route('egresos.index')
            ->with('success', 'Egreso eliminado exitosamente');
    }

    /**
     * Descarga el formato CSV para la carga histórica de egresos.
     */
    public function descargarFormato()
    {
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="formato_carga_egresos.csv"',
        ];

        $callback = function () {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF)); // BOM para UTF-8
            fputcsv($file, ['Año', 'Mes', 'Fecha (DD/MM/YYYY)', 'Tipo', 'Descripción', 'Monto (Bs)']);

            // Ejemplo
            fputcsv($file, ['2023', 'Enero', '05/01/2023', 'Alquiler', 'Alquiler Oficina Central', '2500.00']);
            fputcsv($file, ['2023', 'Febrero', '10/02/2023', 'Servicios', 'Pago de Electricidad', '150.20']);

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Importa los datos desde el archivo CSV.
     */
    public function importarDatos(Request $request)
    {
        $request->validate([
            'archivo_csv' => 'required|file|mimes:csv,txt'
        ]);

        try {
            $file = $request->file('archivo_csv');
            $path = $file->getRealPath();
            $handle = fopen($path, 'r');

            // Ignorar la primera línea
            fgetcsv($handle);

            $importados = 0;
            \DB::beginTransaction();

            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                // Mapeo: 0:Año, 1:Mes, 2:Fecha, 3:Tipo, 4:Descripción, 5:Monto
                if (count($data) < 6)
                    continue;

                $fechaRaw = $data[2];
                try {
                    $fecha = \Carbon\Carbon::createFromFormat('d/m/Y', $fechaRaw)->format('Y-m-d');
                } catch (\Exception $e) {
                    $mes = $this->obtenerMesNumero($data[1]);
                    $fecha = $data[0] . '-' . sprintf('%02d', $mes) . '-01';
                }

                Egreso::create([
                    'Tipo' => $data[3],
                    'Descripcion_egreso' => $data[4],
                    'Fecha_egreso' => $fecha,
                    'Monto_egreso' => (float) $data[5],
                ]);

                $importados++;
            }

            fclose($handle);
            \DB::commit();

            return back()->with('success', "Se han importado {$importados} registros de egresos correctamente.");

        } catch (\Exception $e) {
            \DB::rollBack();
            return back()->with('error', 'Error al importar archivo: ' . $e->getMessage());
        }
    }

    private function obtenerMesNumero($nombre)
    {
        $meses = [
            'Enero' => 1,
            'Febrero' => 2,
            'Marzo' => 3,
            'Abril' => 4,
            'Mayo' => 5,
            'Junio' => 6,
            'Julio' => 7,
            'Agosto' => 8,
            'Septiembre' => 9,
            'Octubre' => 10,
            'Noviembre' => 11,
            'Diciembre' => 12
        ];
        return $meses[ucfirst(strtolower($nombre))] ?? 1;
    }
}
