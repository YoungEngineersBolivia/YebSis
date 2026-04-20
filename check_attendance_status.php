<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\ClasePrueba;

$all = ClasePrueba::where('Asistencia', '!=', 'pendiente')->get();

foreach ($all as $c) {
    echo "ID: " . $c->Id_clasePrueba . " | Asis: " . $c->Asistencia . " | UserID: " . ($c->Id_usuario_asistencia ?? 'NULL') . "\n";
}
