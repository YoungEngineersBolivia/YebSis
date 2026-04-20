<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Usuario;

$usuarios = Usuario::with('persona')->get();

foreach ($usuarios as $u) {
    echo "ID: " . $u->Id_usuarios . " | ";
    echo "Correo: " . $u->Correo . " | ";
    echo "Id_personas: " . ($u->Id_personas ?? 'NULL') . " | ";
    echo "Persona: " . ($u->persona?->nombre_completo ?? 'No vinculada') . "\n";
}
