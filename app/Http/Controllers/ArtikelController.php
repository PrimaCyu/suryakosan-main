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
        $query = Artikel::query();

        if ($request->has('search') && !empty($request->search)) {
            $search = strtolower($request->search);
            $query->where(function($q) use ($search) {
                $q->whereRaw('LOWER(title) LIKE ?', ["%{$search}%"]);
            });
        }

        $artikels = $query->orderByDesc('created_at')->paginate(10, ['*'], 'artikel_page');

        return view('backend.dashboard.artikel.index', compact('artikels'));
    }

    public function searchAjax(Request $request)
    {
        $search = strtolower($request->get('search'));
        $artikels = Artikel::when($search, function ($query, $search) {
                return $query->where(function($q) use ($search) {
                    $q->whereRaw('LOWER(title) LIKE ?', ["%{$search}%"]);
                });
            })
            ->orderByDesc('created_at')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $artikels
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
            'slug'      => 'nullable|string|max:255',
            'image'     => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'deskripsi' => 'nullable|string',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('artikels', 'public');
        }

        Artikel::create([
            'title'     => $request->title,
            'slug'      => $this->generateUniqueSlug($request->title),
            'image'     => $imagePath,
            'deskripsi' => $request->deskripsi,
            'view'      => 0,
        ]);

        Cache::forget('home_latest_artikels');

        return redirect()->back()->with('success', 'Artikel berhasil ditambahkan!');
    }

    public function update(Request $request, Artikel $artikel)
    {
        $request->validate([
            'title'     => 'required|string|max:255',
            'slug'      => 'nullable|string|max:255',
            'image'     => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'deskripsi' => 'nullable|string',
        ]);

        $imagePath = $artikel->image;
        if ($request->hasFile('image')) {
            if ($artikel->image && Storage::disk('public')->exists($artikel->image)) {
                Storage::disk('public')->delete($artikel->image);
            }
            $imagePath = $request->file('image')->store('artikels', 'public');
        }

        $oldSlug = $artikel->slug;
        $artikel->update([
            'title'     => $request->title,
            'slug'      => $this->generateUniqueSlug($request->title, $artikel->id),
            'image'     => $imagePath,
            'deskripsi' => $request->deskripsi,
        ]);

        Cache::forget("artikel_detail_{$oldSlug}");
        Cache::forget("artikel_detail_{$artikel->slug}");
        Cache::forget("artikel_berita_lainnya_{$artikel->id}");
        Cache::forget('home_latest_artikels');

        return redirect()->back()->with('success', 'Artikel berhasil diperbarui!');
    }

    public function delete(Artikel $artikel)
    {
        $slug = $artikel->slug;
        $id = $artikel->id;
        $artikel->delete();

        Cache::forget("artikel_detail_{$slug}");
        Cache::forget("artikel_berita_lainnya_{$id}");
        Cache::forget('home_latest_artikels');

        return redirect()->back()->with('success', 'Artikel berhasil dihapus!');
    }
}
