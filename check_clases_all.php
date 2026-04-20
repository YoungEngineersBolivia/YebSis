<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\ClasePrueba;

$clases = ClasePrueba::with('usuarioAsistencia.persona')->get();

foreach ($clases as $clase) {
    echo "ID: " . $clase->Id_clasePrueba . " | ";
    echo "Asistencia: " . $clase->Asistencia . " | ";
    echo "User: " . ($clase->Id_usuario_asistencia ?? 'NULL') . " | ";
    echo "Nombre: " . ($clase->usuarioAsistencia?->persona?->nombre_completo ?? 'No encontrado') . "\n";
}
