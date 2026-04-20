<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\ClasePrueba;

$clases = ClasePrueba::all();
foreach ($clases as $c) {
    echo "ID: " . $c->Id_clasePrueba . " | Asis: " . $c->Asistencia . " | User: " . ($c->Id_usuario_asistencia === null ? 'NULL' : $c->Id_usuario_asistencia) . "\n";
}
