<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductImageKosan;
use App\Models\ProductKosan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class KosanController extends Controller
{
    private function checkKosanAccess($kosanId)
    {
        $user = auth()->user();
        if (!$user->isSuperAdmin()) {
            $assigned = $user->kosans()->pluck('product_kosans.id')->toArray();
            if (!in_array($kosanId, $assigned)) {
                abort(403, 'Akses ditolak. Anda tidak memiliki wewenang untuk mengelola properti kos ini.');
            }
        }
    }

    public function index(Request $request)
    {
        $user = auth()->user();
        $isSuper = $user->isSuperAdmin();
        $assignedIds = $isSuper ? collect() : $user->kosans()->pluck('product_kosans.id');

        $query = ProductKosan::with(['productImageKosan', 'productKamarKosan'])
            ->when(!$isSuper, function ($q) use ($assignedIds) {
                $q->whereIn('id', $assignedIds);
            });

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
        $user = auth()->user();
        $isSuper = $user->isSuperAdmin();
        $assignedIds = $isSuper ? collect() : $user->kosans()->pluck('product_kosans.id');

        $search = strtolower($request->get('search'));
        $product_kosan = ProductKosan::with(['productImageKosan', 'productKamarKosan'])
            ->when(!$isSuper, function ($q) use ($assignedIds) {
                $q->whereIn('id', $assignedIds);
            })
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
        if (!auth()->user()->isSuperAdmin()) {
            abort(403, 'Akses ditolak. Hanya Super Admin yang berhak menambahkan properti kos-kosan baru.');
        }

        $request->validate([
            'title'       => 'required|string|max:255',
            'wilayah'     => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'tersedia'    => 'nullable|numeric',
            'view'        => 'nullable|numeric',
            'gmaps'       => 'nullable|string',
        ]);

        $fasilitas = $request->fasilitas;
        if (is_array($fasilitas)) {
            $fasilitas = implode(', ', $fasilitas);
        }

        $data = [
            'title'       => $request->title,
            'slug'        => $this->generateUniqueSlug($request->title),
            'description' => $request->description ?? '',
            'fasilitas'   => $fasilitas ?? '',
            'wilayah'     => $request->wilayah ?? 'Bali',
            'tersedia'    => $request->tersedia ?? 0,
            'view'        => $request->view ?? 0,
            'gmaps'       => $request->gmaps
        ];

        $kosan = ProductKosan::create($data);

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $path = $request->file('image')->store('kosan/image', 'public');
            ProductImageKosan::create([
                'product_kosan_id' => $kosan->id,
                'image'            => $path,
            ]);
        }

        Cache::forget('home_kamar_list');

        return back()->with('success', 'Properti Kos-kosan berhasil ditambahkan');
    }

    public function update($product_kosan, Request $request)
    {
        $this->checkKosanAccess($product_kosan);
        $productKosan = ProductKosan::findOrFail($product_kosan);

        $request->validate([
            'title'       => 'required|string|max:255',
            'wilayah'     => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'tersedia'    => 'nullable|numeric',
            'view'        => 'nullable|numeric',
            'gmaps'       => 'nullable|string',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        $fasilitas = $request->fasilitas;
        if (is_array($fasilitas)) {
            $fasilitas = implode(', ', $fasilitas);
        }

        $data = [
            'title'       => $request->title,
            'slug'        => $this->generateUniqueSlug($request->title, $productKosan->id),
            'description' => $request->description ?? '',
            'fasilitas'   => $fasilitas ?? '',
            'wilayah'     => $request->wilayah ?? 'Bali',
            'tersedia'    => $request->tersedia ?? 0,
            'view'        => $request->view ?? 0,
            'gmaps'       => $request->gmaps
        ];

        $productKosan->update($data);

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $path = $request->file('image')->store('kosan/image', 'public');
            ProductImageKosan::create([
                'product_kosan_id' => $productKosan->id,
                'image'            => $path,
            ]);
        }

        Cache::forget('home_kamar_list');
        Cache::forget("kosan_detail_{$productKosan->slug}");

        return back()->with('success', 'Properti Kos-kosan berhasil diperbarui');
    }

    public function delete($product_kosan)
    {
        if (!auth()->user()->isSuperAdmin()) {
            abort(403, 'Akses ditolak. Hanya Super Admin yang berhak menghapus properti kos-kosan.');
        }

        $productKosan = ProductKosan::findOrFail($product_kosan);
        $slug = $productKosan->slug;
        $productKosan->delete();

        Cache::forget('home_kamar_list');
        Cache::forget("kosan_detail_{$slug}");

        return back()->with('success', 'Properti Kos-kosan berhasil dihapus');
    }

    // IMAGE KOSAN
    public function insertImage($product_kosan, Request $request)
    {
        $this->checkKosanAccess($product_kosan);
        $request->validate([
            'images'            => 'nullable|array',
            'images.*'          => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'image'             => 'nullable|array',
            'image.*'           => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'dataImage'         => 'nullable|array',
            'dataImage.*.image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        $uploadedCount = 0;
        DB::transaction(function () use ($request, $product_kosan, &$uploadedCount) {
            $files = [];
            if ($request->hasFile('images')) {
                $f = $request->file('images');
                $files = array_merge($files, is_array($f) ? $f : [$f]);
            }
            if ($request->hasFile('image')) {
                $f = $request->file('image');
                $files = array_merge($files, is_array($f) ? $f : [$f]);
            }

            foreach ($files as $file) {
                if ($file && $file->isValid()) {
                    $path = $file->store('kosan/image', 'public');
                    ProductImageKosan::create([
                        'product_kosan_id' => $product_kosan,
                        'image'            => $path,
                    ]);
                    $uploadedCount++;
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
                        $uploadedCount++;
                    }
                }
            }
        });

        $productKosan = ProductKosan::find($product_kosan);
        if ($productKosan) {
            Cache::forget('home_kamar_list');
            Cache::forget("kosan_detail_{$productKosan->slug}");
        }

        if ($uploadedCount > 0) {
            return back()->with('success', "Berhasil mengunggah {$uploadedCount} foto galeri kosan.");
        }

        return back()->with('warning', 'Tidak ada foto yang diunggah. Pastikan format file JPG, PNG, atau WEBP.');
    }

    public function deleteImage($product_image_kosan)
    {
        $productImage = ProductImageKosan::findOrFail($product_image_kosan);
        $this->checkKosanAccess($productImage->product_kosan_id);
        $productKosan = $productImage->productKosan;

        if ($productImage->image && Storage::disk('public')->exists($productImage->image)) {
            Storage::disk('public')->delete($productImage->image);
        }

        $productImage->delete();

        if ($productKosan) {
            Cache::forget('home_kamar_list');
            Cache::forget("kosan_detail_{$productKosan->slug}");
        }

        return back()->with('success', 'delete success');
    }
}
