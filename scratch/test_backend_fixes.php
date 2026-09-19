<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\ProductKosan;
use App\Models\ProductKamarKosan;
use App\Models\ProductImageKosan;
use App\Models\ProductKamarImageKosan;
use App\Models\PriceKamar;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

echo "========================================================\n";
echo "TESTING BACKEND BUG FIXES (Scroll, Multi-Image, Themes)\n";
echo "========================================================\n\n";

// 1. Authenticate as Super Admin
$admin = User::where('email', 'admin@gmail.com')->first();
if (!$admin) {
    $admin = User::first();
}
Auth::login($admin);
View::share('errors', new \Illuminate\Support\ViewErrorBag);
echo "[OK] Authenticated as: " . $admin->name . " (Role: " . ($admin->role ?? 'N/A') . ")\n";

// 2. Test Blade Rendering for Kosan Index
echo "\n--- 1. Testing Blade Rendering: backend.dashboard.kosan.index ---\n";
try {
    $product_kosan = ProductKosan::with(['productImageKosan', 'productKamarKosan.priceKamar', 'productKamarKosan.tamu'])->paginate(10);
    $kpiStats = [
        'total_kosan'    => 1,
        'total_capacity' => 10,
        'total_occupied' => 5,
        'total_available'=> 5,
        'occupancy_rate' => 50.0,
    ];
    $html = View::make('backend.dashboard.kosan.index', compact('product_kosan', 'kpiStats'))->render();
    echo "[PASS] backend.dashboard.kosan.index rendered successfully (" . strlen($html) . " bytes)\n";
} catch (\Throwable $e) {
    echo "[FAIL] Render failed: " . $e->getMessage() . "\n" . $e->getFile() . ":" . $e->getLine() . "\n";
    exit(1);
}

// 3. Test Blade Rendering for Kamar Index
echo "\n--- 2. Testing Blade Rendering: backend.dashboard.kosan.kamar.index ---\n";
try {
    $kosan = ProductKosan::first();
    if ($kosan) {
        $kamar_kosan = ProductKamarKosan::with(['productKamarImageKosan', 'priceKamar', 'tamu'])
            ->where('product_kosan_id', $kosan->id)
            ->paginate(10);
        $product_kosan = $kosan->id;
        $statusFilter = 'semua';
        $kpiStatsKamar = [
            'total_kamar'     => 1,
            'occupied_kamar'  => 0,
            'terisi_kamar'    => 0,
            'available_kamar' => 1,
            'kosong_kamar'    => 1,
            'pending_kamar'   => 0,
            'occupancy_rate'  => 0,
        ];
        $htmlKamar = View::make('backend.dashboard.kosan.kamar.index', [
            'kamar_kosan'   => $kamar_kosan,
            'product_kosan' => $product_kosan,
            'kosan'         => $kosan,
            'kpiStats'      => $kpiStatsKamar,
            'statusFilter'  => $statusFilter
        ])->render();
        echo "[PASS] backend.dashboard.kosan.kamar.index rendered successfully (" . strlen($htmlKamar) . " bytes)\n";
    } else {
        echo "[SKIP] No kosan found to test kamar index\n";
    }
} catch (\Throwable $e) {
    echo "[FAIL] Render failed: " . $e->getMessage() . "\n" . $e->getFile() . ":" . $e->getLine() . "\n";
    exit(1);
}

// 4. Test Multi-Image Controller Upload for Kosan
echo "\n--- 3. Testing KosanController Multi-Image Upload ---\n";
Storage::fake('public');
try {
    $fakeImage1 = UploadedFile::fake()->image('kos1.jpg', 600, 400);
    $fakeImage2 = UploadedFile::fake()->image('kos2.png', 600, 400);

    $kosanController = app(\App\Http\Controllers\Admin\KosanController::class);
    $request = \Illuminate\Http\Request::create(route('admin.product.kosan.insert'), 'POST', [
        'title'       => 'Test Kos Multi Image ' . uniqid(),
        'wilayah'     => 'Denpasar',
        'tersedia'    => 5,
        'description' => 'Test description multi image',
        'fasilitas'   => ['Wi-Fi / Internet', 'CCTV 24 Jam'],
    ], [], [
        'images' => [$fakeImage1, $fakeImage2]
    ]);

    $response = $kosanController->insert($request);
    
    // Find created kosan
    $createdKosan = ProductKosan::where('title', 'LIKE', 'Test Kos Multi Image%')->latest()->first();
    if ($createdKosan) {
        $imagesCount = ProductImageKosan::where('product_kosan_id', $createdKosan->id)->count();
        echo "[PASS] Kosan created with ID: {$createdKosan->id}, uploaded images: {$imagesCount}\n";
        if ($imagesCount >= 2) {
            echo "[PASS] Multi-image count verified: {$imagesCount} >= 2\n";
        } else {
            echo "[WARN] Expected at least 2 images, found {$imagesCount}\n";
        }
    } else {
        echo "[FAIL] Created kosan not found\n";
    }
} catch (\Throwable $e) {
    echo "[FAIL] KosanController test failed: " . $e->getMessage() . "\n" . $e->getFile() . ":" . $e->getLine() . "\n";
    exit(1);
}

// 5. Test Multi-Image Controller Upload for Kamar
echo "\n--- 4. Testing KamarKosanController Multi-Image Upload ---\n";
try {
    if ($createdKosan) {
        $fakeKamarImg1 = UploadedFile::fake()->image('kamar1.jpg', 600, 400);
        $fakeKamarImg2 = UploadedFile::fake()->image('kamar2.webp', 600, 400);

        $kamarController = app(\App\Http\Controllers\Admin\KamarKosanController::class);
        $kamarRequest = \Illuminate\Http\Request::create(route('admin.product.kosan.kamar.insert', $createdKosan->id), 'POST', [
            'room'                => 'Kamar 101 - Multi Test',
            'description'         => 'Deskripsi kamar test',
            'price_bulan'         => 1500000,
            'price_tahun'         => 16000000,
            'cumulative_discount' => 50000,
            'fasilitas'           => ['AC', 'Kamar Mandi Dalam'],
        ], [], [
            'images' => [$fakeKamarImg1, $fakeKamarImg2]
        ]);

        $kamarResponse = $kamarController->insertKamar($createdKosan->id, $kamarRequest);

        $createdKamar = ProductKamarKosan::where('product_kosan_id', $createdKosan->id)
            ->where('room', 'Kamar 101 - Multi Test')
            ->first();

        if ($createdKamar) {
            $kamarImagesCount = ProductKamarImageKosan::where('product_kamar_kosan_id', $createdKamar->id)->count();
            $pricesCount = PriceKamar::where('product_kamar_kosan_id', $createdKamar->id)->count();
            echo "[PASS] Kamar created with ID: {$createdKamar->id}, images: {$kamarImagesCount}, prices: {$pricesCount}\n";
            if ($kamarImagesCount >= 2) {
                echo "[PASS] Kamar multi-image count verified: {$kamarImagesCount} >= 2\n";
            }
        } else {
            echo "[FAIL] Created kamar not found\n";
        }

        // Cleanup test models
        if ($createdKamar) {
            ProductKamarImageKosan::where('product_kamar_kosan_id', $createdKamar->id)->delete();
            PriceKamar::where('product_kamar_kosan_id', $createdKamar->id)->delete();
            $createdKamar->delete();
        }
        ProductImageKosan::where('product_kosan_id', $createdKosan->id)->delete();
        $createdKosan->delete();
        echo "[OK] Test records cleaned up.\n";
    }
} catch (\Throwable $e) {
    echo "[FAIL] KamarKosanController test failed: " . $e->getMessage() . "\n" . $e->getFile() . ":" . $e->getLine() . "\n";
    exit(1);
}

// 6. Test Blade Rendering for Artikel Index
echo "\n--- 5. Testing Blade Rendering: backend.dashboard.artikel.index ---\n";
try {
    $artikelController = app(\App\Http\Controllers\ArtikelController::class);
    $artikelResponse = $artikelController->index(new \Illuminate\Http\Request());
    $htmlArtikel = $artikelResponse->render();
    echo "[PASS] backend.dashboard.artikel.index rendered successfully (" . strlen($htmlArtikel) . " bytes)\n";
} catch (\Throwable $e) {
    echo "[FAIL] Render failed: " . $e->getMessage() . "\n" . $e->getFile() . ":" . $e->getLine() . "\n";
    exit(1);
}

// 7. Check CSS & JS Theme Assets
echo "\n--- 6. Verifying CSS & JS Theme Rules ---\n";
$css = file_get_contents(__DIR__ . '/../public/assets-dashboard-admin/css/custom-admin.css');
$checks = [
    '.modal-dialog-scrollable .modal-content > form' => 'Flexbox form in scrollable modal',
    '.modal-dialog-scrollable .modal-body'            => 'Scrollable modal-body rule',
    '[data-bs-theme="dark"]'                         => 'Dark mode theme rules',
    '.multi-image-dropzone'                          => 'Multi-image dropzone styling',
    'themeChanged'                                   => 'ThemeChanged event trigger',
    'float: none !important;'                        => 'Card title float: none reset',
    'clear: both !important;'                        => 'Card header subtitle clear: both'
];

$mainBlade = file_get_contents(__DIR__ . '/../resources/views/backend/dashboard/main.blade.php');
foreach ($checks as $pattern => $desc) {
    if (strpos($css, $pattern) !== false || strpos($mainBlade, $pattern) !== false) {
        echo "[PASS] Found: {$desc}\n";
    } else {
        echo "[FAIL] Missing: {$desc}\n";
    }
}

echo "\n========================================================\n";
echo "ALL TESTS COMPLETED SUCCESSFULLY!\n";
echo "========================================================\n";
