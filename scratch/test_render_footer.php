<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\ProductKosan;
use App\Models\SosialMedia;
use App\Models\Testimoni;

try {
    $rendered = view('frontend.footer')->render();
    echo "✔ footer.blade.php rendered successfully! Length: " . strlen($rendered) . " bytes\n";
} catch (\Throwable $e) {
    echo "❌ Render error in footer: " . $e->getMessage() . "\n";
    exit(1);
}
