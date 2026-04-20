<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Usuario;

$user = Usuario::with('persona')->find(1);
if ($user) {
    echo "User 1 loaded.\n";
    echo "Persona ID: " . ($user->Id_personas ?? 'NULL') . "\n";
    echo "Persona Obj: " . ($user->persona ? 'Present' : 'NULL') . "\n";
    if ($user->persona) {
        echo "Persona Nombre: " . ($user->persona->Nombre ?? 'NULL') . "\n";
        echo "Persona Apellido: " . ($user->persona->Apellido ?? 'NULL') . "\n";
        echo "Nombre Completo: " . ($user->persona->nombre_completo ?? 'NULL') . "\n";
    }
} else {
    echo "User 1 not found.\n";
}
