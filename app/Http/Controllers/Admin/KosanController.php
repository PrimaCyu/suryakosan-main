<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductImageKosan;
use App\Models\ProductKosan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class KosanController extends Controller
{
    public function index(Request $request)
    {
        $query = ProductKosan::with(['productImageKosan', 'productKamarKosan']);
        if ($request->has('search') && !empty($request->search)) {
            $search = strtolower($request->search);
            $query->where(function($q) use ($search) {
                $q->whereRaw('LOWER(title) LIKE ?', ["%{$search}%"])
                  ->orWhereRaw('LOWER(wilayah) LIKE ?', ["%{$search}%"])
                  ->orWhereRaw('LOWER(fasilitas) LIKE ?', ["%{$search}%"])
                  ->orWhereRaw('LOWER(description) LIKE ?', ["%{$search}%"]);
            });
        }
        $product_kosan = $query->orderByDesc('created_at')->paginate(10, ['*'], 'kosan_page');

        return view('backend.dashboard.kosan.index', compact('product_kosan'));
    }

    public function searchKosan(Request $request)
    {
        $search = strtolower($request->get('search'));
        $product_kosan = ProductKosan::with(['productImageKosan', 'productKamarKosan'])
            ->when($search, function ($query, $search) {
                return $query->where(function($q) use ($search) {
                    $q->whereRaw('LOWER(title) LIKE ?', ["%{$search}%"]);
                });
            })
            ->orderByDesc('created_at')
            ->paginate(10);

        return response()->json($product_kosan);
    }

    private function generateUniqueSlug($title, $ignoreId = null)
    {
        $baseSlug = Str::slug($title);
        $slug = $baseSlug;
        $count = 1;

        while (ProductKosan::where('slug', $slug)->when($ignoreId, function ($query, $id) {
            return $query->where('id', '!=', $id);
        })->exists()) {
            $slug = "{$baseSlug}-{$count}";
            $count++;
        }

        return $slug;
    }

    public function insert(Request $request)
    {
        $fasilitas = $request->fasilitas;
        if (is_array($fasilitas)) {
            $fasilitas = implode(', ', $fasilitas);
        }

        $data = [
            'title'       => $request->title,
            'slug'        => $this->generateUniqueSlug($request->title),
            'description' => $request->description,
            'fasilitas'   => $fasilitas,
            'wilayah'     => $request->wilayah,
            'tersedia'    => $request->tersedia,
            'view'        => $request->view,
            'gmaps'       => $request->gmaps
        ];

        ProductKosan::create($data);
        Cache::forget('home_kamar_list');

        return back()->with('success', 'Properti Kos-kosan berhasil ditambahkan');
    }

    public function update($product_kosan, Request $request)
    {
        $productKosan = ProductKosan::findOrFail($product_kosan);

        $fasilitas = $request->fasilitas;
        if (is_array($fasilitas)) {
            $fasilitas = implode(', ', $fasilitas);
        }

        $data = [
            'title'       => $request->title,
            'slug'        => $this->generateUniqueSlug($request->title, $productKosan->id),
            'description' => $request->description,
            'fasilitas'   => $fasilitas,
            'wilayah'     => $request->wilayah,
            'tersedia'    => $request->tersedia,
            'view'        => $request->view,
            'gmaps'       => $request->gmaps
        ];

        $productKosan->update($data);
        Cache::forget('home_kamar_list');
        Cache::forget("kosan_detail_{$productKosan->slug}");

        return back()->with('success', 'Properti Kos-kosan berhasil diperbarui');
    }

    public function delete($product_kosan)
    {
        $productKosan = ProductKosan::findOrFail($product_kosan);
        $slug = $productKosan->slug;
        $productKosan->delete();

        Cache::forget('home_kamar_list');
        Cache::forget("kosan_detail_{$slug}");

        return back()->with('success', 'delete success');
    }

    // IMAGE KOSAN
    public function insertImage($product_kosan, Request $request)
    {
        $request->validate([
            'dataImage'         => 'nullable|array',
            'dataImage.*.image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'images'            => 'nullable|array',
            'images.*'          => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        DB::transaction(function () use ($request, $product_kosan) {
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $file) {
                    if ($file->isValid()) {
                        $path = $file->store('kosan/image', 'public');
                        ProductImageKosan::create([
                            'product_kosan_id' => $product_kosan,
                            'image'            => $path,
                        ]);
                    }
                }
            }

            if ($request->hasFile('dataImage')) {
                foreach ($request->file('dataImage') as $item) {
                    if (isset($item['image']) && $item['image']->isValid()) {
                        $path = $item['image']->store('kosan/image', 'public');
                        ProductImageKosan::create([
                            'product_kosan_id' => $product_kosan,
                            'image'            => $path,
                        ]);
                    }
                }
            }
        });

        $productKosan = ProductKosan::find($product_kosan);
        if ($productKosan) {
            Cache::forget('home_kamar_list');
            Cache::forget("kosan_detail_{$productKosan->slug}");
        }

        return back()->with('success', 'Gambar kosan berhasil diunggah.');
    }

    public function deleteImage($product_image_kosan)
    {
        $productImage = ProductImageKosan::findOrFail($product_image_kosan);
        $productKosan = $productImage->productKosan;
        $productImage->delete();

        if ($productKosan) {
            Cache::forget('home_kamar_list');
            Cache::forget("kosan_detail_{$productKosan->slug}");
        }

        return back()->with('success', 'delete success');
    }
}
