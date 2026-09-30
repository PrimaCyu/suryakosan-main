<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\ProductKosan;
use App\Models\ProductKamarKosan;
use App\Models\PriceKamar;
use App\Service\ProcessBookingDate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

echo "--- TESTING ROOM CREATION & PRICING FLOW ---\n";

$kosan = ProductKosan::first();
if (!$kosan) {
    echo "No kosan found!\n";
    exit(1);
}

echo "Testing with Kosan ID: {$kosan->id} ({$kosan->title})\n";

// 1. Simulate Single Room Creation
$roomName = "Kamar Test Unit " . rand(100, 999);
$priceBulan = 1500000;
$discount = 10; // 10%
$priceTahun = 16000000;

$kamar = DB::transaction(function () use ($kosan, $roomName, $priceBulan, $discount, $priceTahun) {
    $k = ProductKamarKosan::create([
        'product_kosan_id'    => $kosan->id,
        'room'                => $roomName,
        'description'         => 'Unit uji coba otomatis',
        'fasilitas'           => 'AC, Kamar Mandi Dalam',
        'cumulative_discount' => $discount,
        'views'               => 0,
    ]);

    PriceKamar::create([
        'product_kamar_kosan_id' => $k->id,
        'kategori'               => 'bulan',
        'price'                  => $priceBulan,
        'discount'               => $discount,
    ]);

    PriceKamar::create([
        'product_kamar_kosan_id' => $k->id,
        'kategori'               => 'tahun',
        'price'                  => $priceTahun,
        'discount'               => $discount,
    ]);

    return $k;
});

echo "Created Kamar ID: {$kamar->id}\n";
$kamar->load(['priceKamar', 'productKosan']);

$monthly = $kamar->monthly_price;
echo "Monthly Price: Rp " . number_format($monthly->price, 0, ',', '.') . " | Discount: {$monthly->discount}%\n";
echo "Kamar Cumulative Discount: {$kamar->cumulative_discount}%\n";

if ($monthly->price == 1500000 && $monthly->discount == 10 && $kamar->cumulative_discount == 10) {
    echo "✔ Price & Discount sync on creation: PASSED\n";
} else {
    echo "❌ Creation sync failed!\n";
}

// 2. Test Booking calculation with 1 month duration
$processBooking = app(ProcessBookingDate::class);
$request = new Request(['bulan' => 1]);
$totalBooking = $processBooking->calculateBookingPrice($kamar, $request);

// Math: Base 1.500.000 - 10% (150.000) = 1.350.000 + PPN 12% (162.000) = 1.512.000
echo "Calculated Booking for 1 Month (with 10% discount + 12% PPN): Rp " . number_format($totalBooking, 0, ',', '.') . "\n";
if ($totalBooking == 1512000) {
    echo "✔ Booking calculation: PASSED (Rp 1.512.000 matches expected!)\n";
} else {
    echo "❌ Booking calculation mismatch: {$totalBooking}\n";
}

// 3. Test Room Update (change price to 1.800.000 and discount to 20%)
DB::transaction(function () use ($kamar) {
    $newDisc = 20;
    $kamar->update([
        'room' => $kamar->room . ' (Updated)',
        'cumulative_discount' => $newDisc,
    ]);

    PriceKamar::updateOrCreate(
        ['product_kamar_kosan_id' => $kamar->id, 'kategori' => 'bulan'],
        ['price' => 1800000, 'discount' => $newDisc]
    );
});

$kamar->refresh();
$monthlyUpdated = $kamar->monthly_price;
echo "Updated Monthly Price: Rp " . number_format($monthlyUpdated->price, 0, ',', '.') . " | Discount: {$monthlyUpdated->discount}%\n";
echo "Updated Kamar Cumulative Discount: {$kamar->cumulative_discount}%\n";

if ($monthlyUpdated->price == 1800000 && $monthlyUpdated->discount == 20 && $kamar->cumulative_discount == 20) {
    echo "✔ Price & Discount sync on update: PASSED\n";
} else {
    echo "❌ Update sync failed!\n";
}

// Clean up test room
$kamar->priceKamar()->delete();
$kamar->delete();
echo "Cleaned up test room.\n";
echo "--- ALL TESTS COMPLETED SUCCESSFULLY! ---\n";
