<?php

namespace App\Http\Controllers;

use App\Models\Artikel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ArtikelController extends Controller
{
    public function index(Request $request)
    {
        // 1. Hitung Ringkasan KPI Stats
        $totalArtikels = Artikel::count();
        $totalViews = (int) Artikel::sum('view');
        $avgViews = $totalArtikels > 0 ? (int) round($totalViews / $totalArtikels) : 0;
        $topArtikel = Artikel::orderByDesc('view')->first();

        $kpiStats = [
            'total_artikels' => $totalArtikels,
            'total_views'    => $totalViews,
            'avg_views'      => $avgViews,
            'top_title'      => $topArtikel ? $topArtikel->title : '-',
            'top_views'      => $topArtikel ? $topArtikel->view : 0,
            'top_slug'       => $topArtikel ? $topArtikel->slug : '',
        ];

        // 2. Query Pencarian & Pengurutan
        $query = Artikel::query();

        if ($request->has('search') && !empty($request->search)) {
            $search = strtolower($request->search);
            $query->where(function($q) use ($search) {
                $q->whereRaw('LOWER(title) LIKE ?', ["%{$search}%"])
                  ->orWhereRaw('LOWER(deskripsi) LIKE ?', ["%{$search}%"]);
            });
        }

        $sort = $request->get('sort', 'latest');
        switch ($sort) {
            case 'popular':
                $query->orderByDesc('view');
                break;
            case 'oldest':
                $query->orderBy('created_at');
                break;
            case 'title_asc':
                $query->orderBy('title');
                break;
            case 'latest':
            default:
                $query->orderByDesc('created_at');
                break;
        }

        $artikels = $query->paginate(10)->withQueryString();

        return view('backend.dashboard.artikel.index', compact('artikels', 'kpiStats', 'sort'));
    }

    public function searchAjax(Request $request)
    {
        $search = strtolower($request->get('search', ''));
        $sort = $request->get('sort', 'latest');

        $query = Artikel::query();

        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->whereRaw('LOWER(title) LIKE ?', ["%{$search}%"])
                  ->orWhereRaw('LOWER(deskripsi) LIKE ?', ["%{$search}%"]);
            });
        }

        switch ($sort) {
            case 'popular':
                $query->orderByDesc('view');
                break;
            case 'oldest':
                $query->orderBy('created_at');
                break;
            case 'title_asc':
                $query->orderBy('title');
                break;
            case 'latest':
            default:
                $query->orderByDesc('created_at');
                break;
        }

        $artikels = $query->take(25)->get()->map(function($item) {
            $item->detail_url = route('news.detail', $item->slug);
            $item->update_url = route('admin.artikel.update', $item->id);
            $item->delete_url = route('admin.artikel.delete', $item->id);
            return $item;
        });

        return response()->json([
            'status' => 'success',
            'data'   => $artikels
        ]);
    }

    private function generateUniqueSlug($title, $ignoreId = null)
    {
        $baseSlug = Str::slug($title);
        $slug = $baseSlug;
        $count = 1;

        while (Artikel::where('slug', $slug)->when($ignoreId, function ($query, $id) {
            return $query->where('id', '!=', $id);
        })->exists()) {
            $slug = "{$baseSlug}-{$count}";
            $count++;
        }

        return $slug;
    }

    public function insert(Request $request)
    {
        $request->validate([
            'title'     => 'required|string|max:255',
            'image'     => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'deskripsi' => 'required|string|min:10',
        ], [
            'title.required'     => 'Judul artikel wajib diisi.',
            'title.max'          => 'Judul artikel maksimal 255 karakter.',
            'image.image'        => 'File sampul harus berupa gambar valid.',
            'image.mimes'        => 'Format gambar yang didukung: JPG, JPEG, PNG, GIF, WEBP.',
            'image.max'          => 'Ukuran gambar maksimal 5MB.',
            'deskripsi.required' => 'Konten / isi artikel wajib diisi.',
            'deskripsi.min'      => 'Konten artikel minimal 10 karakter.',
        ]);

        $imagePath = null;
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $imagePath = $request->file('image')->store('artikels', 'public');
        }

        $artikel = Artikel::create([
            'title'     => strip_tags($request->title),
            'slug'      => $this->generateUniqueSlug($request->title),
            'image'     => $imagePath,
            'deskripsi' => $request->deskripsi,
            'view'      => 0,
        ]);

        Cache::forget('home_latest_artikels');

        return redirect()->back()->with('success', 'Artikel "' . $artikel->title . '" berhasil dipublikasikan!');
    }

    public function update(Request $request, Artikel $artikel)
    {
        $request->validate([
            'title'     => 'required|string|max:255',
            'image'     => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'deskripsi' => 'required|string|min:10',
        ], [
            'title.required'     => 'Judul artikel wajib diisi.',
            'title.max'          => 'Judul artikel maksimal 255 karakter.',
            'image.image'        => 'File sampul harus berupa gambar valid.',
            'image.mimes'        => 'Format gambar yang didukung: JPG, JPEG, PNG, GIF, WEBP.',
            'image.max'          => 'Ukuran gambar maksimal 5MB.',
            'deskripsi.required' => 'Konten / isi artikel wajib diisi.',
            'deskripsi.min'      => 'Konten artikel minimal 10 karakter.',
        ]);

        $imagePath = $artikel->image;
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            if ($artikel->image && Storage::disk('public')->exists($artikel->image)) {
                Storage::disk('public')->delete($artikel->image);
            }
            $imagePath = $request->file('image')->store('artikels', 'public');
        }

        $oldSlug = $artikel->slug;
        $newSlug = $this->generateUniqueSlug($request->title, $artikel->id);

        $artikel->update([
            'title'     => strip_tags($request->title),
            'slug'      => $newSlug,
            'image'     => $imagePath,
            'deskripsi' => $request->deskripsi,
        ]);

        Cache::forget("artikel_detail_{$oldSlug}");
        Cache::forget("artikel_detail_{$artikel->slug}");
        Cache::forget("artikel_berita_lainnya_{$artikel->id}");
        Cache::forget('home_latest_artikels');

        return redirect()->back()->with('success', 'Artikel "' . $artikel->title . '" berhasil diperbarui!');
    }

    public function delete(Artikel $artikel)
    {
        $title = $artikel->title;
        $slug = $artikel->slug;
        $id = $artikel->id;

        // Model deleting event listener handles image file deletion from disk
        $artikel->delete();

        Cache::forget("artikel_detail_{$slug}");
        Cache::forget("artikel_berita_lainnya_{$id}");
        Cache::forget('home_latest_artikels');

        return redirect()->back()->with('success', 'Artikel "' . $title . '" berhasil dihapus!');
    }
}
