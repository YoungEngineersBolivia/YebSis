<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\ClasePrueba;
use App\Models\Usuario;

$clase = ClasePrueba::first();
if ($clase) {
    $user = Usuario::first();
    echo "Marcando clase " . $clase->Id_clasePrueba . " con usuario " . $user->Id_usuarios . " (" . $user->Correo . ")\n";

    $clase->Asistencia = 'asistio';
    $clase->Id_usuario_asistencia = $user->Id_usuarios;
    $clase->save();

    $claseFresh = ClasePrueba::with('usuarioAsistencia.persona')->find($clase->Id_clasePrueba);
    echo "Asistencia: " . $claseFresh->Asistencia . "\n";
    echo "ID Usuario: " . $claseFresh->Id_usuario_asistencia . "\n";
    echo "Relacion Usuario: " . ($claseFresh->usuarioAsistencia ? 'Cargada' : 'NULL') . "\n";
    echo "Relacion Persona: " . ($claseFresh->usuarioAsistencia?->persona ? 'Cargada' : 'NULL') . "\n";
    echo "Nombre Completo: " . ($claseFresh->usuarioAsistencia?->persona?->nombre_completo ?? 'N/A') . "\n";
} else {
    echo "No hay clases de prueba.\n";
}
