<?php

namespace App\Console\Commands;

use App\Models\Tamu;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ExpirePendingBookings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'kosan:expire-pending {--hours=2 : Batas toleransi jam untuk booking status pending}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Membatalkan secara otomatis reservasi pending yang melebihi batas waktu demi menjaga ketersediaan kamar';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $hours = (int) $this->option('hours');
        if ($hours < 1) {
            $hours = 2;
        }

        $threshold = now()->subHours($hours);
        $staleBookings = Tamu::with('productKamarKosan.productKosan')
            ->where('status', 'pending')
            ->where('created_at', '<=', $threshold)
            ->get();

        if ($staleBookings->isEmpty()) {
            $this->info("Tidak ada booking pending yang kedaluwarsa (threshold: > {$hours} jam).");
            return 0;
        }

        $count = 0;
        $kosanIds = [];

        foreach ($staleBookings as $booking) {
            $booking->update([
                'status'           => 'reject',
                'rejection_reason' => "Batas waktu verifikasi/pembayaran habis (otomatis kedaluwarsa setelah {$hours} jam).",
                'processed_at'     => now(),
            ]);

            if ($booking->productKamarKosan && $booking->productKamarKosan->product_kosan_id) {
                $kosanIds[] = $booking->productKamarKosan->product_kosan_id;
            }
            $count++;
        }

        // Sinkronkan ketersediaan kamar pada kosan terkait
        $affectedKosans = \App\Models\ProductKosan::whereIn('id', array_unique($kosanIds))->get();
        foreach ($affectedKosans as $kosan) {
            $kosan->syncAvailableCount();
        }

        Cache::forget('home_kamar_list');
        Cache::forget('home_kosan_list');

        $this->info("Berhasil membatalkan {$count} booking pending yang kedaluwarsa.");
        Log::info("Command kosan:expire-pending membatalkan {$count} booking pending kedaluwarsa.");

        return 0;
    }
}
