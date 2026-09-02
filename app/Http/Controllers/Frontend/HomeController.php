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
    public function index()
    {
        $kamarList = Cache::remember('home_kamar_list', 3600, function () {
            return ProductKamarKosan::with(['productKosan.productImageKosan', 'productKamarImageKosan', 'priceKamar'])
                        ->orderByDesc('views')
                        ->orderByDesc('created_at')
                        ->get();
        });

        $testimonis = Cache::remember('home_testimonis', 3600, function () {
            return Testimoni::orderByDesc('created_at')->get();
        });

        return view('frontend.index', compact('kamarList', 'testimonis'));
    }

    public function kosanIndex(Request $request)
    {
        $query = ProductKosan::with(['productImageKosan', 'productKamarKosan.priceKamar']);

        if ($request->has('search') && !empty($request->search)) {
            $search = strtolower($request->search);
            $query->where(function($q) use ($search) {
                $q->whereRaw('LOWER(title) LIKE ?', ["%{$search}%"])
                  ->orWhereRaw('LOWER(wilayah) LIKE ?', ["%{$search}%"])
                  ->orWhereRaw('LOWER(fasilitas) LIKE ?', ["%{$search}%"]);
            });
        }

        if ($request->has('wilayah') && $request->wilayah != 'semua' && !empty($request->wilayah)) {
            $query->where('wilayah', $request->wilayah);
        }

        $kosanList = $query->orderByDesc('created_at')->paginate(9)->withQueryString();
        return view('frontend.kosan.kos', compact('kosanList'));
    }

    public function kosanDetail($slug)
    {
        $kosan = Cache::remember("kosan_detail_{$slug}", 3600, function () use ($slug) {
            return ProductKosan::with(['productImageKosan', 'productKamarKosan.productKamarImageKosan', 'productKamarKosan.priceKamar'])
                        ->where('slug', $slug)
                        ->firstOrFail();
        });

        return view('frontend.kosan.detail-kos', compact('kosan'));
    }

    public function kamarDetail($product_kamar_kosan = null)
    {
        $kamar = null;
        if ($product_kamar_kosan) {
            $kamar = ProductKamarKosan::with(['productKosan', 'productKamarImageKosan', 'priceKamar'])->find($product_kamar_kosan);
            if ($kamar) {
                $kamar->increment('views');
                Cache::forget('home_kamar_list');
            }
        }
        return view('frontend.kosan.kamar.detail-kamar', compact('kamar'));
    }

    public function newsIndex(Request $request)
    {
        $query = Artikel::query();

        if ($request->has('search') && !empty($request->search)) {
            $search = strtolower($request->search);
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

        $beritaLainnya = Cache::remember("artikel_berita_lainnya_{$artikel->id}", 3600, function () use ($artikel) {
            return Artikel::where('id', '!=', $artikel->id)
                                ->orderByDesc('created_at')
                                ->take(5)
                                ->get();
        });

        return view('frontend.news.detail-news', compact('artikel', 'beritaLainnya'));
    }
}
