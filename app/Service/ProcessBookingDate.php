<?php

namespace App\Service;

use App\Models\ProductKamarKosan;
use App\Models\Tamu;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ProcessBookingDate
{
    /**
     * Menghitung rentang tanggal booking dan memvalidasi ketersediaan jadwal kamar.
     *
     * @param int|string $product_kamar_kosan
     * @param Request $request
     * @param int|string|null $ignoreTamuId Digunakan saat perpanjangan/renew booking
     * @return array
     */
    public function calculateBookingRange($product_kamar_kosan, Request $request, $ignoreTamuId = null)
    {
        $jam    = max(0, (int) $request->input('jam', 0));
        $hari   = max(0, (int) $request->input('hari', 0));
        $minggu = max(0, (int) $request->input('minggu', 0));
        $bulan  = max(0, (int) $request->input('bulan', 0));
        $tahun  = max(0, (int) $request->input('tahun', 0));

        if (($jam + $hari + $minggu + $bulan + $tahun) <= 0) {
            return [
                'status'  => false,
                'message' => 'Pilih minimal salah satu durasi sewa (jam, hari, minggu, bulan, atau tahun).'
            ];
        }

        try {
            $startDateTimeString = $request->start_date . ' ' . ($request->start_time ?: '12:00:00');
            $startDate = Carbon::parse($startDateTimeString);
        } catch (\Exception $e) {
            return [
                'status'  => false,
                'message' => 'Format tanggal atau waktu masuk tidak valid.'
            ];
        }

        $endDate = $startDate->copy()
                    ->addYears($tahun)
                    ->addMonths($bulan)
                    ->addWeeks($minggu)
                    ->addDays($hari)
                    ->addHours($jam);

        // Dua interval waktu [A_start, A_end] dan [B_start, B_end] saling bertabrakan jika:
        // A_start < B_end DAN A_end > B_start
        $isBooked = Tamu::where('product_kamar_kosan_id', $product_kamar_kosan)
            ->whereIn('status', ['pending', 'approved'])
            ->when($ignoreTamuId, function ($query, $id) {
                return $query->where('id', '!=', $id);
            })
            ->where(function ($query) use ($startDate, $endDate) {
                $query->where('start_date', '<', $endDate->toDateTimeString())
                      ->where('end_date', '>', $startDate->toDateTimeString());
            })
            ->exists();

        if ($isBooked) {
            return [
                'status'  => false,
                'message' => 'Kamar sudah terisi atau sudah dibooking pada rentang jadwal tersebut. Silakan pilih tanggal/jam lain.'
            ];
        }

        return [
            'status' => true,
            'start'  => $startDate->toDateTimeString(),
            'end'    => $endDate->toDateTimeString()
        ];
    }

    /**
     * Menghitung total harga sewa secara aman di server (anti manipulasi client).
     *
     * @param ProductKamarKosan $kamar
     * @param Request $request
     * @return float
     */
    public function calculateBookingPrice(ProductKamarKosan $kamar, Request $request): float
    {
        $durationMap = [
            'jam'    => max(0, (int) $request->input('jam', 0)),
            'hari'   => max(0, (int) $request->input('hari', 0)),
            'minggu' => max(0, (int) $request->input('minggu', 0)),
            'bulan'  => max(0, (int) $request->input('bulan', 0)),
            'tahun'  => max(0, (int) $request->input('tahun', 0)),
        ];

        $filled = array_filter($durationMap, fn($qty) => $qty > 0);
        $isSingleInput = count($filled) === 1;
        $isMultipleInputs = count($filled) > 1;

        $totalBasePrice = 0.0;
        $totalDiscountAmount = 0.0;
        $prices = $kamar->priceKamar;

        foreach ($durationMap as $cat => $qty) {
            if ($qty > 0) {
                $pInfo = $this->getCategoryPriceAndDiscount($prices, $cat);
                $subtotal = $pInfo['price'] * $qty;
                $totalBasePrice += $subtotal;

                if ($isSingleInput) {
                    $discVal = $pInfo['discount'];
                    if ($discVal <= 0 && $cat === 'bulan') {
                        $discVal = (float) ($kamar->cumulative_discount ?? 0);
                    }
                    if ($discVal > 0) {
                        if ($discVal <= 100) {
                            $totalDiscountAmount = ($subtotal * $discVal) / 100;
                        } else {
                            $totalDiscountAmount = $discVal * $qty;
                        }
                    }
                }
            }
        }

        $cumulativeDiscount = (float) ($kamar->cumulative_discount ?? 0);
        if ($isMultipleInputs && $cumulativeDiscount > 0) {
            if ($cumulativeDiscount <= 100) {
                $totalDiscountAmount = ($totalBasePrice * $cumulativeDiscount) / 100;
            } else {
                $totalDiscountAmount = $cumulativeDiscount;
            }
        }

        if ($totalDiscountAmount > $totalBasePrice) {
            $totalDiscountAmount = $totalBasePrice;
        }

        $priceAfterDiscount = $totalBasePrice - $totalDiscountAmount;
        $ppnAmount = $priceAfterDiscount * 0.12; // PPN 12%
        $grandTotal = $priceAfterDiscount + $ppnAmount;

        return round($grandTotal, 2);
    }

    /**
     * Mencari harga dan diskon berdasarkan nama kategori durasi.
     *
     * @param iterable $prices
     * @param string $categoryName
     * @return array
     */
    private function getCategoryPriceAndDiscount($prices, string $categoryName): array
    {
        $key = strtolower(trim($categoryName));
        if ($prices) {
            foreach ($prices as $item) {
                $itemCat = strtolower(trim($item->kategori ?? ''));
                if ($itemCat === $key || str_starts_with($itemCat, $key) || str_starts_with($key, $itemCat)) {
                    return [
                        'price'    => (float) ($item->price ?? 0),
                        'discount' => (float) ($item->discount ?? 0),
                    ];
                }
            }
        }

        return ['price' => 0.0, 'discount' => 0.0];
    }
}

