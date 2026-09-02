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

    // KAMAR
    public function indexKamar($product_kosan, Request $request)
    {
        $kosan = ProductKosan::find($product_kosan);
        $query = ProductKamarKosan::with(['productKamarImageKosan', 'priceKamar', 'tamu'])
            ->where('product_kosan_id', $product_kosan);
        if ($request->has('search') && !empty($request->search)) {
            $query->whereRaw('LOWER(room) LIKE ?', ['%' . strtolower($request->search) . '%']);
        }
        $kamar_kosan = $query->paginate(10);
        return view('backend.dashboard.kosan.kamar.index', compact('kamar_kosan', 'product_kosan', 'kosan'));
    }

    public function searchKamar($product_kosan, Request $request)
    {
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
        $dataKamar = $request->input('dataKamar', []);

        DB::transaction(function () use ($dataKamar, $product_kosan) {
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
                    'cumulative_discount' => $item['cumulative_discount'] ?? 0,
                    'views'               => $item['views'] ?? 0,
                ]);
            }
        });

        $kosan = ProductKosan::find($product_kosan);
        if ($kosan) {
            \Illuminate\Support\Facades\Cache::forget('home_kamar_list');
            \Illuminate\Support\Facades\Cache::forget("kosan_detail_{$kosan->slug}");
        }

        return back()->with('success', 'Data kamar berhasil ditambahkan.');
    }

    public function updateKamar($product_kosan, $product_kamar_kosan, Request $request)
    {
        $kamar_kosan = ProductKamarKosan::where([
            'id'               => $product_kamar_kosan,
            'product_kosan_id' => $product_kosan
        ])->firstOrFail();

        $fasilitas = $request->input('fasilitas');
        if (is_array($fasilitas)) {
            $fasilitas = implode(', ', $fasilitas);
        }

        DB::transaction(function () use ($kamar_kosan, $request, $fasilitas) {
            $kamar_kosan->update([
                'room'                => $request->input('room'),
                'cumulative_discount' => $request->input('cumulative_discount', 0),
                'description'         => $request->input('description'),
                'fasilitas'           => $fasilitas,
                'views'               => $request->input('views', $kamar_kosan->views ?? 0),
            ]);
        });

        $kosan = ProductKosan::find($product_kosan);
        if ($kosan) {
            \Illuminate\Support\Facades\Cache::forget('home_kamar_list');
            \Illuminate\Support\Facades\Cache::forget("kosan_detail_{$kosan->slug}");
        }

        return back()->with('success', 'Data kamar berhasil diperbarui.');
    }

    public function deleteKamar($product_kosan, $product_kamar_kosan)
    {
        $kamar_kosan = ProductKamarKosan::where([
            'id'               => $product_kamar_kosan,
            'product_kosan_id' => $product_kosan
        ])->firstOrFail();

        $kosan = ProductKosan::find($product_kosan);
        $kamar_kosan->delete();

        if ($kosan) {
            \Illuminate\Support\Facades\Cache::forget('home_kamar_list');
            \Illuminate\Support\Facades\Cache::forget("kosan_detail_{$kosan->slug}");
        }

        return back()->with('success', 'delete success');
    }

    // FASILITAS KAMAR
    public function insertFasilitas($product_kosan, $product_kamar_kosan, Request $request)
    {
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
        $fasilitasKamar = FasilitasKamar::findOrFail($fasilitas_kamar);
        $fasilitasKamar->update([
            'product_kamar_kosan_id' => $fasilitasKamar->product_kamar_kosan_id,
            'title'                  => $request->title
        ]);

        return back()->with('success', 'update fasilitas success');
    }

    public function deleteFasilitas($product_kosan, $product_kamar_kosan, $fasilitas_kamar)
    {
        $fasilitasKamar = FasilitasKamar::findOrFail($fasilitas_kamar);
        $fasilitasKamar->delete();
        return back()->with('success', 'fasilitas delete success');
    }

    // PRODUCT KAMAR IMAGE KOSAN
    public function insertKamarImage($product_kosan, $product_kamar_kosan, Request $request)
    {
        $request->validate([
            'images'                 => 'nullable|array',
            'images.*'               => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'dataImageKamar'         => 'nullable|array',
            'dataImageKamar.*.image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        DB::transaction(function () use ($request, $product_kamar_kosan) {
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $file) {
                    if ($file->isValid()) {
                        $path = $file->store('kosan/kamar', 'public');
                        ProductKamarImageKosan::create([
                            'product_kamar_kosan_id' => $product_kamar_kosan,
                            'image'                  => $path,
                        ]);
                    }
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
                    }
                }
            }
        });

        return back()->with('success', 'Foto kamar berhasil diunggah.');
    }

    public function deleteKamarImage($product_kosan, $product_kamar_kosan, $product_kamar_image_kosan)
    {
        $image_kamar = ProductKamarImageKosan::findOrFail($product_kamar_image_kosan);
        $image_kamar->delete();

        return back()->with('success', 'image delete success');
    }

    // PRICE KAMAR
    public function insertPriceKamar($product_kosan, $product_kamar_kosan, Request $request)
    {
        $dataPriceKamar = $request->input('priceKamar', []);
        foreach ($dataPriceKamar as $item) {
            PriceKamar::create([
                'product_kamar_kosan_id' => $product_kamar_kosan,
                'kategori'               => $item['kategori'],
                'price'                  => $item['price'],
                'discount'               => $item['discount']
            ]);
        }

        return back()->with('success', 'price kamar insert success');
    }

    public function updatePriceKamar($product_kosan, $product_kamar_kosan, $price_kamar, Request $request)
    {
        $price_kamar = PriceKamar::findOrFail($price_kamar);
        $price_kamar->update([
            'product_kamar_kosan_id' => $product_kamar_kosan,
            'kategori'               => $request->kategori,
            'price'                  => $request->price,
            'discount'               => $request->discount
        ]);

        return back()->with('success', 'price kamar update success');
    }

    public function deletePriceKamar($product_kosan, $product_kamar_kosan, $price_kamar)
    {
        $price_kamar = PriceKamar::findOrFail($price_kamar);
        $price_kamar->delete();
        return back()->with('success', 'price kamar delete success');
    }

    // TAMU/BOOKING ANGGOTA KAMAR
    public function indexTamu($product_kosan, $product_kamar_kosan)
    {
        $tamu = Tamu::where([
            'product_kamar_kosan_id' => $product_kamar_kosan,
            'status'                 => 'approved'
        ])->get();

        return response()->json($tamu);
    }

    public function renewTamu($product_kosan, $product_kamar_kosan, $tamu, Request $request)
    {
        $dataTamu = Tamu::findOrFail($tamu);
        $bookingDate = $this->processBookingDates->calculateBookingRange($request);

        $data = [
            'product_kamar_kosan_id' => $dataTamu->product_kamar_kosan_id,
            'name'                   => $request->name,
            'telp'                   => $request->telp,
            'email'                  => $request->email,
            'start_time'             => $dataTamu->start_time,
            'start_date'             => $bookingDate['start'],
            'end_date'               => $bookingDate['end'],
            'payment_method'         => $dataTamu->payment_method,
            'total_price'            => $request->total_price,
        ];

        if ($request->hasFile('proof_of_transfer')) {
            if ($dataTamu->proof_of_transfer && Storage::disk('public')->exists($dataTamu->proof_of_transfer)) {
                Storage::disk('public')->delete($dataTamu->proof_of_transfer);
            }
            $path = $request->file('proof_of_transfer')->store('tamu/proof-of-transfer', 'public');
            $data['proof_of_transfer'] = $path;
        }

        $dataTamu->update($data);

        return back()->with('success', 'success renew ' . $dataTamu->name);
    }

    public function deleteTamu($product_kosan, $product_kamar_kosan, $tamu)
    {
        $dataTamu = Tamu::findOrFail($tamu);
        if ($dataTamu->proof_of_transfer && Storage::disk('public')->exists($dataTamu->proof_of_transfer)) {
            Storage::disk('public')->delete($dataTamu->proof_of_transfer);
        }
        $dataTamu->delete();
        return back()->with('success', 'tamu delete success');
    }
}
