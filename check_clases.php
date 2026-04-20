<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\ClasePrueba;

$clases = ClasePrueba::where('Asistencia', '!=', 'pendiente')->with('usuarioAsistencia.persona')->get();

foreach ($clases as $clase) {
    echo "ID: " . $clase->Id_clasePrueba . "\n";
    echo "Asistencia: " . $clase->Asistencia . "\n";
    echo "Id_usuario_asistencia: " . ($clase->Id_usuario_asistencia ?? 'NULL') . "\n";
    echo "Nombre: " . ($clase->usuarioAsistencia?->persona?->nombre_completo ?? 'No encontrado') . "\n";
    echo "-------------------\n";
}
