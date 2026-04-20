<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\ClasePrueba;

$clase = ClasePrueba::find(1);
if ($clase) {
    echo "Clase ID: " . $clase->Id_clasePrueba . "\n";
    echo "Asistencia: " . $clase->Asistencia . "\n";
    echo "Id_usuario_asistencia: " . ($clase->Id_usuario_asistencia ?? 'NULL') . "\n";

    $user = $clase->usuarioAsistencia;
    if ($user) {
        echo "Usuario encontrado: ID " . $user->Id_usuarios . " | " . $user->Correo . "\n";
        $persona = $user->persona;
        if ($persona) {
            echo "Persona encontrada: " . $persona->nombre_completo . "\n";
        } else {
            echo "Persona NO encontrada para este usuario.\n";
        }
    } else {
        echo "Usuario NO encontrado por la relación.\n";
    }
} else {
    echo "Clase no encontrada.\n";
}
