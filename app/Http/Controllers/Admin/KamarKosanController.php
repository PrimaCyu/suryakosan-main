<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FasilitasKamar;
use App\Models\PriceKamar;
use App\Models\ProductKamarImageKosan;
use App\Models\ProductKamarKosan;
use App\Models\ProductKosan;
use App\Models\Tamu;
use App\Service\ProcessBookingDate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class KamarKosanController extends Controller
{
    protected $processBookingDates;

    public function __construct(ProcessBookingDate $processBookingDates)
    {
        $this->processBookingDates = $processBookingDates;
    }

    private function checkKosanAccess($kosanId)
    {
        $user = auth()->user();
        if (!$user->isSuperAdmin()) {
            $assigned = $user->kosans()->pluck('product_kosans.id')->toArray();
            if (!in_array($kosanId, $assigned)) {
                abort(403, 'Akses ditolak. Anda tidak memiliki wewenang untuk mengelola kamar di kosan ini.');
            }
        }
    }

    // KAMAR
    public function indexKamar($product_kosan, Request $request)
    {
        $this->checkKosanAccess($product_kosan);
        $kosan = ProductKosan::findOrFail($product_kosan);

        // Hitung Ringkasan Metrik Okupansi (KPI Cards) untuk Kosan ini
        $allRooms = ProductKamarKosan::with(['tamu', 'priceKamar'])
            ->where('product_kosan_id', $product_kosan)
            ->get();

        $totalKamarCount = $allRooms->count();
        $occupiedKamarCount = $allRooms->filter(fn($r) => $r->active_tenant !== null)->count();
        $pendingKamarCount = $allRooms->filter(fn($r) => $r->pending_tenant !== null)->count();
        $availableKamarCount = max(0, $totalKamarCount - $occupiedKamarCount);
        $occupancyRate = $totalKamarCount > 0 ? round(($occupiedKamarCount / $totalKamarCount) * 100, 1) : 0;

        $kpiStats = [
            'total_kamar'     => $totalKamarCount,
            'occupied_kamar'  => $occupiedKamarCount,
            'terisi_kamar'    => $occupiedKamarCount,
            'available_kamar' => $availableKamarCount,
            'kosong_kamar'    => $availableKamarCount,
            'pending_kamar'   => $pendingKamarCount,
            'occupancy_rate'  => $occupancyRate,
        ];

        // Query tabel kamar dengan relasi lengkap
        $query = ProductKamarKosan::with([
            'productKamarImageKosan',
            'priceKamar',
            'tamu' => function ($q) {
                $q->orderByDesc('end_date');
            }
        ])->where('product_kosan_id', $product_kosan);

        if ($request->has('search') && !empty($request->search)) {
            $query->whereRaw('LOWER(room) LIKE ?', ['%' . strtolower($request->search) . '%']);
        }

        // Filter status kamar
        $statusFilter = $request->get('status', 'semua');
        if ($statusFilter === 'terisi') {
            $query->whereHas('tamu', function ($q) {
                $q->where('status', 'approved')
                  ->whereDate('end_date', '>=', now()->toDateString());
            });
        } elseif ($statusFilter === 'kosong') {
            $query->whereDoesntHave('tamu', function ($q) {
                $q->where('status', 'approved')
                  ->whereDate('end_date', '>=', now()->toDateString());
            });
        } elseif ($statusFilter === 'pending') {
            $query->whereHas('tamu', function ($q) {
                $q->where('status', 'pending');
            });
        }

        $kamar_kosan = $query->orderBy('room', 'asc')->paginate(10);

        return view('backend.dashboard.kosan.kamar.index', compact('kamar_kosan', 'product_kosan', 'kosan', 'kpiStats', 'statusFilter'));
    }

    public function searchKamar($product_kosan, Request $request)
    {
        $this->checkKosanAccess($product_kosan);
        $search = strtolower($request->get('search'));
        $kamar_kosan = ProductKamarKosan::with(['productKamarImageKosan', 'priceKamar', 'tamu'])
            ->where('product_kosan_id', $product_kosan)
            ->when($search, function ($query, $search) {
                return $query->whereRaw('LOWER(room) LIKE ?', ["%{$search}%"]);
            })
            ->orderByDesc('created_at')
            ->paginate(10);

        return response()->json($kamar_kosan);
    }

    public function insertKamar($product_kosan, Request $request)
    {
        $this->checkKosanAccess($product_kosan);

        // Dukung input single room terpadu dari modal terpadu baru ATAU multi-row array legacy
        if ($request->has('room')) {
            $request->validate([
                'room'                => 'required|string|max:255',
                'description'         => 'nullable|string',
                'cumulative_discount' => 'nullable|numeric|min:0',
                'price_bulan'         => 'nullable|numeric|min:0',
                'price_tahun'         => 'nullable|numeric|min:0',
                'image'               => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
                'images'              => 'nullable|array',
                'images.*'            => 'image|mimes:jpeg,png,jpg,webp|max:5120',
            ]);

            $isSuperAdmin = auth()->user()->isSuperAdmin();
            $fasilitas = $request->input('fasilitas');
            if (is_array($fasilitas)) {
                $fasilitas = implode(', ', $fasilitas);
            }

            DB::transaction(function () use ($request, $product_kosan, $fasilitas, $isSuperAdmin) {
                $kamar = ProductKamarKosan::create([
                    'product_kosan_id'    => $product_kosan,
                    'room'                => $request->input('room'),
                    'description'         => $request->input('description'),
                    'fasilitas'           => $fasilitas,
                    'cumulative_discount' => $isSuperAdmin ? ($request->input('cumulative_discount') ?? 0) : 0,
                    'views'               => 0,
                ]);

                // Auto-create Tarif Bulanan jika diisi
                if ($request->filled('price_bulan') && (float)$request->input('price_bulan') > 0) {
                    PriceKamar::create([
                        'product_kamar_kosan_id' => $kamar->id,
                        'kategori'               => 'bulan',
                        'price'                  => (float)$request->input('price_bulan'),
                        'discount'               => 0,
                    ]);
                }

                // Auto-create Tarif Tahunan jika diisi
                if ($request->filled('price_tahun') && (float)$request->input('price_tahun') > 0) {
                    PriceKamar::create([
                        'product_kamar_kosan_id' => $kamar->id,
                        'kategori'               => 'tahun',
                        'price'                  => (float)$request->input('price_tahun'),
                        'discount'               => 0,
                    ]);
                }

                // Auto-upload Foto Kamar (multi-image atau single image fallback)
                if ($request->hasFile('images')) {
                    foreach ($request->file('images') as $file) {
                        if ($file && $file->isValid()) {
                            $path = $file->store('kosan/kamar', 'public');
                            ProductKamarImageKosan::create([
                                'product_kamar_kosan_id' => $kamar->id,
                                'image'                  => $path,
                            ]);
                        }
                    }
                } elseif ($request->hasFile('image') && $request->file('image')->isValid()) {
                    $path = $request->file('image')->store('kosan/kamar', 'public');
                    ProductKamarImageKosan::create([
                        'product_kamar_kosan_id' => $kamar->id,
                        'image'                  => $path,
                    ]);
                }
            });
        } elseif ($request->has('dataKamar')) {
            $request->validate([
                'dataKamar'                       => 'required|array|min:1',
                'dataKamar.*.room'                => 'required|string|max:255',
                'dataKamar.*.description'         => 'nullable|string',
                'dataKamar.*.cumulative_discount' => 'nullable|numeric|min:0',
                'dataKamar.*.views'               => 'nullable|integer|min:0',
            ]);

            $isSuperAdmin = auth()->user()->isSuperAdmin();
            $dataKamar = $request->input('dataKamar', []);

            DB::transaction(function () use ($dataKamar, $product_kosan, $isSuperAdmin) {
                foreach ($dataKamar as $item) {
                    $fasilitas = $item['fasilitas'] ?? null;
                    if (is_array($fasilitas)) {
                        $fasilitas = implode(', ', $fasilitas);
                    }

                    ProductKamarKosan::create([
                        'product_kosan_id'    => $product_kosan,
                        'room'                => $item['room'],
                        'description'         => $item['description'] ?? null,
                        'fasilitas'           => $fasilitas,
                        'cumulative_discount' => $isSuperAdmin ? ($item['cumulative_discount'] ?? 0) : 0,
                        'views'               => $item['views'] ?? 0,
                    ]);
                }
            });
        } else {
            return back()->with('failed', 'Data kamar tidak valid.');
        }

        $kosan = ProductKosan::find($product_kosan);
        if ($kosan) {
            $kosan->syncAvailableCount();
            \Illuminate\Support\Facades\Cache::forget('home_kamar_list');
            \Illuminate\Support\Facades\Cache::forget("kosan_detail_{$kosan->slug}");
        }

        return back()->with('success', 'Data kamar berhasil ditambahkan.');
    }

    public function updateKamar($product_kosan, $product_kamar_kosan, Request $request)
    {
        $this->checkKosanAccess($product_kosan);
        $kamar_kosan = ProductKamarKosan::where([
            'id'               => $product_kamar_kosan,
            'product_kosan_id' => $product_kosan
        ])->firstOrFail();

        $request->validate([
            'room'                => 'required|string|max:255',
            'cumulative_discount' => 'nullable|numeric|min:0',
            'description'         => 'nullable|string',
            'views'               => 'nullable|integer|min:0',
            'image'               => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'images'              => 'nullable|array',
            'images.*'            => 'image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        $isSuperAdmin = auth()->user()->isSuperAdmin();
        $fasilitas = $request->input('fasilitas');
        if (is_array($fasilitas)) {
            $fasilitas = implode(', ', $fasilitas);
        }

        DB::transaction(function () use ($kamar_kosan, $request, $fasilitas, $isSuperAdmin) {
            $kamar_kosan->update([
                'room'                => $request->input('room'),
                'cumulative_discount' => $isSuperAdmin ? $request->input('cumulative_discount', 0) : ($kamar_kosan->cumulative_discount ?? 0),
                'description'         => $request->input('description'),
                'fasilitas'           => $fasilitas,
                'views'               => $request->input('views', $kamar_kosan->views ?? 0),
            ]);

            // Auto-upload Foto Kamar saat update (multi-image atau single image fallback)
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $file) {
                    if ($file && $file->isValid()) {
                        $path = $file->store('kosan/kamar', 'public');
                        ProductKamarImageKosan::create([
                            'product_kamar_kosan_id' => $kamar_kosan->id,
                            'image'                  => $path,
                        ]);
                    }
                }
            } elseif ($request->hasFile('image') && $request->file('image')->isValid()) {
                $path = $request->file('image')->store('kosan/kamar', 'public');
                ProductKamarImageKosan::create([
                    'product_kamar_kosan_id' => $kamar_kosan->id,
                    'image'                  => $path,
                ]);
            }
        });

        $kosan = ProductKosan::find($product_kosan);
        if ($kosan) {
            $kosan->syncAvailableCount();
            \Illuminate\Support\Facades\Cache::forget('home_kamar_list');
            \Illuminate\Support\Facades\Cache::forget("kosan_detail_{$kosan->slug}");
        }

        return back()->with('success', 'Data kamar berhasil diperbarui.');
    }

    public function deleteKamar($product_kosan, $product_kamar_kosan)
    {
        $this->checkKosanAccess($product_kosan);
        $kamar_kosan = ProductKamarKosan::where([
            'id'               => $product_kamar_kosan,
            'product_kosan_id' => $product_kosan
        ])->firstOrFail();

        $kosan = ProductKosan::find($product_kosan);
        $kamar_kosan->delete();

        if ($kosan) {
            $kosan->syncAvailableCount();
            \Illuminate\Support\Facades\Cache::forget('home_kamar_list');
            \Illuminate\Support\Facades\Cache::forget("kosan_detail_{$kosan->slug}");
        }

        return back()->with('success', 'Kamar berhasil dihapus.');
    }

    // FASILITAS KAMAR
    public function insertFasilitas($product_kosan, $product_kamar_kosan, Request $request)
    {
        $this->checkKosanAccess($product_kosan);
        $request->validate([
            'fasilitasKamar'         => 'required|array|min:1',
            'fasilitasKamar.*.title' => 'required|string|max:255',
        ]);

        $dataFasilitas = $request->input('fasilitasKamar', []);
        foreach ($dataFasilitas as $item) {
            FasilitasKamar::create([
                'product_kamar_kosan_id' => $product_kamar_kosan,
                'title'                  => $item['title']
            ]);
        }

        return back()->with('success', 'fasilitas insert success');
    }

    public function updateFasilitas($product_kosan, $product_kamar_kosan, $fasilitas_kamar, Request $request)
    {
        $this->checkKosanAccess($product_kosan);
        $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $fasilitasKamar = FasilitasKamar::findOrFail($fasilitas_kamar);
        $fasilitasKamar->update([
            'product_kamar_kosan_id' => $fasilitasKamar->product_kamar_kosan_id,
            'title'                  => $request->title
        ]);

        return back()->with('success', 'update fasilitas success');
    }

    public function deleteFasilitas($product_kosan, $product_kamar_kosan, $fasilitas_kamar)
    {
        $this->checkKosanAccess($product_kosan);
        $fasilitasKamar = FasilitasKamar::findOrFail($fasilitas_kamar);
        $fasilitasKamar->delete();
        return back()->with('success', 'fasilitas delete success');
    }

    // PRODUCT KAMAR IMAGE KOSAN
    public function insertKamarImage($product_kosan, $product_kamar_kosan, Request $request)
    {
        $this->checkKosanAccess($product_kosan);
        $request->validate([
            'images'                 => 'nullable|array',
            'images.*'               => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'image'                  => 'nullable|array',
            'image.*'                => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'dataImageKamar'         => 'nullable|array',
            'dataImageKamar.*.image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        $uploadedCount = 0;
        DB::transaction(function () use ($request, $product_kamar_kosan, &$uploadedCount) {
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
                    $path = $file->store('kosan/kamar', 'public');
                    ProductKamarImageKosan::create([
                        'product_kamar_kosan_id' => $product_kamar_kosan,
                        'image'                  => $path,
                    ]);
                    $uploadedCount++;
                }
            }

            if ($request->hasFile('dataImageKamar')) {
                foreach ($request->file('dataImageKamar') as $item) {
                    if (isset($item['image']) && $item['image']->isValid()) {
                        $path = $item['image']->store('kosan/kamar', 'public');
                        ProductKamarImageKosan::create([
                            'product_kamar_kosan_id' => $product_kamar_kosan,
                            'image'                  => $path,
                        ]);
                        $uploadedCount++;
                    }
                }
            }
        });

        if ($uploadedCount > 0) {
            return back()->with('success', "Berhasil mengunggah {$uploadedCount} foto kamar.");
        }

        return back()->with('warning', 'Tidak ada foto yang diunggah. Pastikan format file valid.');
    }

    public function deleteKamarImage($product_kosan, $product_kamar_kosan, $product_kamar_image_kosan)
    {
        $this->checkKosanAccess($product_kosan);
        $image_kamar = ProductKamarImageKosan::findOrFail($product_kamar_image_kosan);
        if ($image_kamar->image && Storage::disk('public')->exists($image_kamar->image)) {
            Storage::disk('public')->delete($image_kamar->image);
        }
        $image_kamar->delete();

        return back()->with('success', 'image delete success');
    }

    // PRICE KAMAR
    public function insertPriceKamar($product_kosan, $product_kamar_kosan, Request $request)
    {
        $this->checkKosanAccess($product_kosan);
        $request->validate([
            'priceKamar'            => 'required|array|min:1',
            'priceKamar.*.kategori' => 'required|string|in:bulan,tahun,Bulanan,Tahunan',
            'priceKamar.*.price'    => 'required|numeric|min:0',
            'priceKamar.*.discount' => 'nullable|numeric|min:0',
        ]);

        $isSuperAdmin = auth()->user()->isSuperAdmin();
        $dataPriceKamar = $request->input('priceKamar', []);
        foreach ($dataPriceKamar as $item) {
            $kategoriNormalized = strtolower(trim($item['kategori']));
            if ($kategoriNormalized === 'bulanan') $kategoriNormalized = 'bulan';
            if ($kategoriNormalized === 'tahunan') $kategoriNormalized = 'tahun';

            PriceKamar::create([
                'product_kamar_kosan_id' => $product_kamar_kosan,
                'kategori'               => $kategoriNormalized,
                'price'                  => $item['price'],
                'discount'               => $isSuperAdmin ? ($item['discount'] ?? 0) : 0
            ]);
        }

        return back()->with('success', 'Harga kamar berhasil ditambahkan.');
    }

    public function updatePriceKamar($product_kosan, $product_kamar_kosan, $price_kamar, Request $request)
    {
        $this->checkKosanAccess($product_kosan);
        $request->validate([
            'kategori' => 'required|string|in:bulan,tahun,Bulanan,Tahunan',
            'price'    => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
        ]);

        $isSuperAdmin = auth()->user()->isSuperAdmin();
        $price_kamar = PriceKamar::findOrFail($price_kamar);

        $kategoriNormalized = strtolower(trim($request->kategori));
        if ($kategoriNormalized === 'bulanan') $kategoriNormalized = 'bulan';
        if ($kategoriNormalized === 'tahunan') $kategoriNormalized = 'tahun';

        $price_kamar->update([
            'product_kamar_kosan_id' => $product_kamar_kosan,
            'kategori'               => $kategoriNormalized,
            'price'                  => $request->price,
            'discount'               => $isSuperAdmin ? ($request->discount ?? 0) : ($price_kamar->discount ?? 0)
        ]);

        return back()->with('success', 'Harga kamar berhasil diperbarui.');
    }

    public function deletePriceKamar($product_kosan, $product_kamar_kosan, $price_kamar)
    {
        $this->checkKosanAccess($product_kosan);
        $price_kamar = PriceKamar::findOrFail($price_kamar);
        $price_kamar->delete();
        return back()->with('success', 'price kamar delete success');
    }

    // TAMU/BOOKING ANGGOTA KAMAR
    public function indexTamu($product_kosan, $product_kamar_kosan)
    {
        $this->checkKosanAccess($product_kosan);
        $tamu = Tamu::where([
            'product_kamar_kosan_id' => $product_kamar_kosan,
            'status'                 => 'approved'
        ])->get();

        return response()->json($tamu);
    }

    public function renewTamu($product_kosan, $product_kamar_kosan, $tamu, Request $request)
    {
        $this->checkKosanAccess($product_kosan);
        $dataTamu = Tamu::findOrFail($tamu);

        $request->validate([
            'name'              => 'required|string|max:255',
            'telp'              => 'required|string|max:25',
            'email'             => 'required|email|max:255',
            'start_date'        => 'required|date',
            'payment_method'    => 'nullable|string',
            'proof_of_transfer' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $bookingDate = $this->processBookingDates->calculateBookingRange($product_kamar_kosan, $request, $dataTamu->id);
        if (isset($bookingDate['status']) && $bookingDate['status'] === false) {
            return back()->withInput()->with('failed', $bookingDate['message']);
        }

        $kamar = ProductKamarKosan::with('priceKamar')->find($product_kamar_kosan);
        $serverCalculatedPrice = $kamar ? $this->processBookingDates->calculateBookingPrice($kamar, $request) : ($request->total_price ?: $dataTamu->total_price);

        $data = [
            'product_kamar_kosan_id' => $dataTamu->product_kamar_kosan_id,
            'name'                   => strip_tags($request->name),
            'telp'                   => strip_tags($request->telp),
            'email'                  => filter_var($request->email, FILTER_SANITIZE_EMAIL),
            'start_time'             => $request->start_time ?: $dataTamu->start_time,
            'start_date'             => $bookingDate['start'],
            'end_date'               => $bookingDate['end'],
            'payment_method'         => $request->payment_method ?: ($dataTamu->payment_method ?: 'cash'),
            'total_price'            => $serverCalculatedPrice,
        ];

        if ($request->hasFile('proof_of_transfer') && $request->file('proof_of_transfer')->isValid()) {
            if ($dataTamu->proof_of_transfer && Storage::disk('public')->exists($dataTamu->proof_of_transfer)) {
                Storage::disk('public')->delete($dataTamu->proof_of_transfer);
            }
            $path = $request->file('proof_of_transfer')->store('tamu/proof-of-transfer', 'public');
            $data['proof_of_transfer'] = $path;
        }

        $dataTamu->update($data);

        $kosan = ProductKosan::find($product_kosan);
        if ($kosan) {
            $kosan->syncAvailableCount();
        }

        $formattedEndDate = \Carbon\Carbon::parse($bookingDate['end'])->isoFormat('D MMMM Y');
        return back()->with('success', "Masa sewa atas nama {$dataTamu->name} berhasil diperpanjang hingga {$formattedEndDate}.");
    }

    public function deleteTamu($product_kosan, $product_kamar_kosan, $tamu)
    {
        $this->checkKosanAccess($product_kosan);
        $dataTamu = Tamu::findOrFail($tamu);
        if ($dataTamu->proof_of_transfer && Storage::disk('public')->exists($dataTamu->proof_of_transfer)) {
            Storage::disk('public')->delete($dataTamu->proof_of_transfer);
        }
        $dataTamu->delete();

        $kosan = ProductKosan::find($product_kosan);
        if ($kosan) {
            $kosan->syncAvailableCount();
        }

        return back()->with('success', 'Data penyewa berhasil dihapus.');
    }
}
