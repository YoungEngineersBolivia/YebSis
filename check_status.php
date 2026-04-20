<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Publicacion;

$pubs = Publicacion::orderBy('Id_publicaciones', 'desc')->limit(5)->get();
foreach ($pubs as $pub) {
    echo "ID: {$pub->Id_publicaciones}, Estado: '{$pub->Estado}' (type: " . gettype($pub->Estado) . ")\n";
}
