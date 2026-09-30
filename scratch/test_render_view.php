<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\ProductKosan;
use App\Models\ProductKamarKosan;
use Illuminate\Support\Facades\Auth;

$user = User::where('role', 'super_admin')->first() ?: User::first();
Auth::login($user);

$kosan = ProductKosan::first();
if (!$kosan) {
    echo "No kosan\n";
    exit(0);
}

$product_kosan = $kosan->id;
$kamar_kosan = ProductKamarKosan::with(['productKamarImageKosan', 'priceKamar', 'tamu'])
    ->where('product_kosan_id', $product_kosan)
    ->paginate(10);

$kpiStats = [
    'total_kamar'     => 10,
    'occupied_kamar'  => 5,
    'terisi_kamar'    => 5,
    'available_kamar' => 5,
    'kosong_kamar'    => 5,
    'pending_kamar'   => 0,
    'occupancy_rate'  => 50.0,
];

$statusFilter = 'semua';

try {
    $rendered = view('backend.dashboard.kosan.kamar.index', compact('kamar_kosan', 'product_kosan', 'kosan', 'kpiStats', 'statusFilter'))->render();
    echo "✔ View rendered successfully! Length: " . strlen($rendered) . " bytes\n";
} catch (\Throwable $e) {
    echo "❌ Render error: " . $e->getMessage() . " in " . $e->getFile() . ":" . $e->getLine() . "\n";
    exit(1);
}
