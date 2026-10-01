<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Artikel;
use App\Models\ProductKamarKosan;
use App\Models\ProductKosan;
use App\Models\Testimoni;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    /**
     * Escape karakter wildcard LIKE agar user tidak bisa inject pattern.
     */
    private function escapeLike(string $value): string
    {
        return str_replace(['%', '_', '\\'], ['\\%', '\\_', '\\\\'], $value);
    }

    public function index()
    {
        $kamarList = Cache::remember('home_kamar_list', 3600, function () {
            return ProductKamarKosan::with(['productKosan.productImageKosan', 'productKamarImageKosan', 'priceKamar', 'tamu'])
                        ->orderByDesc('views')
                        ->orderByDesc('created_at')
                        ->get();
        });

        $kosanList = Cache::remember('home_kosan_list', 3600, function () {
            return ProductKosan::with(['productImageKosan', 'productKamarKosan.priceKamar'])->get();
        });

        $testimonis = Cache::remember('home_testimonis', 3600, function () {
            return Testimoni::orderByDesc('created_at')->get();
        });

        $artikels = Cache::remember('home_latest_artikels', 3600, function () {
            return Artikel::orderByDesc('created_at')->take(3)->get();
        });

        return view('frontend.index', compact('kamarList', 'kosanList', 'testimonis', 'artikels'));
    }

    public function kosanIndex(Request $request)
    {
        $query = ProductKosan::with(['productImageKosan', 'productKamarKosan.priceKamar']);

        if ($request->has('search') && !empty($request->search)) {
            $search = $this->escapeLike(strtolower($request->search));
            $query->where(function($q) use ($search) {
                $q->whereRaw('LOWER(title) LIKE ?', ["%{$search}%"])
                  ->orWhereRaw('LOWER(wilayah) LIKE ?', ["%{$search}%"])
                  ->orWhereRaw('LOWER(fasilitas) LIKE ?', ["%{$search}%"])
                  ->orWhereHas('productKamarKosan', function($sub) use ($search) {
                      $sub->whereRaw('LOWER(room) LIKE ?', ["%{$search}%"])
                          ->orWhereRaw('LOWER(fasilitas) LIKE ?', ["%{$search}%"]);
                  });
            });
        }

        if ($request->has('wilayah') && $request->wilayah != 'semua' && !empty($request->wilayah)) {
            $query->where('wilayah', $request->wilayah);
        }

        if ($request->filled('price_min') || $request->filled('price_max')) {
            $min = $request->price_min;
            $max = $request->price_max;
            $query->whereHas('productKamarKosan.priceKamar', function ($q) use ($min, $max) {
                if (!empty($min)) {
                    $q->where('price', '>=', (float)$min);
                }
                if (!empty($max)) {
                    $q->where('price', '<=', (float)$max);
                }
            });
        } elseif ($request->filled('price_range')) {
            $rangeMap = [
                'under-500'  => [null, 500000],
                '500-1000'   => [500000, 1000000],
                '1000-1500'  => [1000000, 1500000],
                '1500-2500'  => [1500000, 2500000],
                'over-2500'  => [2500000, null],
            ];
            if (isset($rangeMap[$request->price_range])) {
                [$min, $max] = $rangeMap[$request->price_range];
                $query->whereHas('productKamarKosan.priceKamar', function ($q) use ($min, $max) {
                    $q->whereRaw('LOWER(kategori) = ?', ['bulan']);
                    if (!is_null($min)) {
                        $q->where('price', '>=', $min);
                    }
                    if (!is_null($max)) {
                        $q->where('price', '<=', $max);
                    }
                });
            }
        }

        $kosanList = $query->orderByDesc('created_at')->paginate(9)->withQueryString();
        return view('frontend.kosan.kos', compact('kosanList'));
    }

    public function kosanDetail($slug)
    {
        $kosan = ProductKosan::with([
            'productImageKosan',
            'productKamarKosan.productKamarImageKosan',
            'productKamarKosan.priceKamar',
            'productKamarKosan.tamu' => function ($t) {
                $t->where('status', 'approved')
                  ->whereDate('end_date', '>=', now()->toDateString());
            }
        ])
        ->where('slug', $slug)
        ->firstOrFail();

        // Increment view count realistically
        $kosan->increment('view');

        // Related Kosan in same or other regions for exploration/cross-selling
        $relatedKosans = ProductKosan::with(['productImageKosan', 'productKamarKosan.priceKamar'])
            ->where('id', '!=', $kosan->id)
            ->take(3)
            ->get();

        return view('frontend.kosan.detail-kos', compact('kosan', 'relatedKosans'));
    }

    public function kamarDetail($product_kamar_kosan = null)
    {
        if (!$product_kamar_kosan) {
            return redirect()->route('kosan.index');
        }

        $kamar = ProductKamarKosan::with([
            'productKosan.productImageKosan',
            'productKosan.productKamarKosan' => function ($q) use ($product_kamar_kosan) {
                $q->where('id', '!=', $product_kamar_kosan)
                  ->with(['productKamarImageKosan', 'priceKamar']);
            },
            'productKamarImageKosan',
            'priceKamar',
            'tamu' => function ($t) {
                $t->where('status', 'approved')
                  ->whereDate('end_date', '>=', now()->toDateString());
            }
        ])->findOrFail($product_kamar_kosan);
        $kamar->increment('views');

        return view('frontend.kosan.kamar.detail-kamar', compact('kamar'));
    }

    /**
     * Form booking kamar kos (dipindahkan dari route closure).
     */
    public function formBooking($product_kamar_kosan)
    {
        $kamar = ProductKamarKosan::with(['priceKamar', 'productKosan', 'tamu', 'productKamarImageKosan'])
            ->findOrFail($product_kamar_kosan);

        return view('frontend.kosan.kamar.form-booking', compact('kamar'));
    }

    /**
     * API: Cek tanggal ketersediaan kamar (dipindahkan dari route closure).
     */
    public function checkDateKamar($id)
    {
        $kamar = ProductKamarKosan::findOrFail($id);

        $tamu = $kamar->tamu()
                        ->whereIn('status', ['approved', 'pending'])
                        ->select('start_date', 'end_date')
                        ->get();

        return response()->json($tamu);
    }

    public function newsIndex(Request $request)
    {
        $query = Artikel::query();

        if ($request->has('search') && !empty($request->search)) {
            $search = $this->escapeLike(strtolower($request->search));
            $query->whereRaw('LOWER(title) LIKE ?', ["%{$search}%"]);
        }

        $artikels = $query->orderByDesc('created_at')->paginate(6)->withQueryString();
        return view('frontend.news.news', compact('artikels'));
    }

    public function newsDetail($slug)
    {
        $artikel = Cache::remember("artikel_detail_{$slug}", 3600, function () use ($slug) {
            return Artikel::where('slug', $slug)->firstOrFail();
        });

        $artikel->increment('view');

        // Clear cache setelah increment agar views terupdate
        Cache::forget("artikel_detail_{$slug}");

        $beritaLainnya = Cache::remember("artikel_berita_lainnya_{$artikel->id}", 3600, function () use ($artikel) {
            return Artikel::where('id', '!=', $artikel->id)
                                ->orderByDesc('created_at')
                                ->take(5)
                                ->get();
        });

        return view('frontend.news.detail-news', compact('artikel', 'beritaLainnya'));
    }
}

