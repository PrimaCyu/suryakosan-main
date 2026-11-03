<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PaymentMethodController extends Controller
{
    /**
     * Tampilkan daftar seluruh metode pembayaran.
     */
    public function index(Request $request)
    {
        $query = PaymentMethod::query();

        if ($request->filled('search')) {
            $search = strtolower($request->search);
            $query->where(function ($q) use ($search) {
                $q->whereRaw('LOWER(name) LIKE ?', ["%{$search}%"])
                  ->orWhereRaw('LOWER(code) LIKE ?', ["%{$search}%"])
                  ->orWhereRaw('LOWER(account_number) LIKE ?', ["%{$search}%"])
                  ->orWhereRaw('LOWER(account_holder) LIKE ?', ["%{$search}%"]);
            });
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $paymentMethods = $query->orderBy('sort_order', 'asc')
            ->orderBy('id', 'asc')
            ->paginate(15)
            ->withQueryString();

        $activeCount = PaymentMethod::where('is_active', true)->count();
        $totalCount  = PaymentMethod::count();

        return view('backend.dashboard.payment-methods.index', compact('paymentMethods', 'activeCount', 'totalCount'));
    }

    /**
     * Simpan metode pembayaran baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'           => 'required|string|max:255',
            'type'           => 'required|string|in:qris,bank,ewallet,cash',
            'code'           => 'nullable|string|max:50|regex:/^[a-zA-Z0-9_\-]+$/|unique:payment_methods,code',
            'bank_code'      => 'nullable|string|max:20',
            'account_number' => 'nullable|string|max:100',
            'account_holder' => 'nullable|string|max:255',
            'qr_image'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'instructions'   => 'nullable|string|max:1000',
            'is_active'      => 'nullable|boolean',
            'sort_order'     => 'nullable|integer|min:0',
        ], [
            'name.required'     => 'Nama metode/bank wajib diisi.',
            'type.required'     => 'Pilih tipe metode pembayaran.',
            'code.unique'       => 'Kode unik metode sudah digunakan, silakan gunakan kode lain.',
            'code.regex'        => 'Kode unik hanya boleh berupa huruf, angka, tanda minus (-), atau garis bawah (_).',
            'qr_image.image'    => 'File QRIS harus berupa gambar valid.',
            'qr_image.mimes'    => 'Format gambar QRIS yang didukung: JPG, JPEG, PNG, WEBP.',
            'qr_image.max'      => 'Ukuran gambar QRIS maksimal 2 MB agar mudah dimuat penyewa.',
        ]);

        // Generate kode unik jika tidak diisi manual
        $code = $request->code ? Str::slug($request->code, '_') : Str::slug($request->name, '_');
        if (PaymentMethod::where('code', $code)->exists()) {
            $code = $code . '_' . rand(10, 99);
        }

        $bankCode = $request->bank_code;
        if (empty($bankCode)) {
            $bankCode = match ($request->type) {
                'qris'    => 'QRIS',
                'cash'    => 'COD',
                default   => strtoupper(substr(preg_replace('/[^A-Za-z]/', '', $request->name), 0, 4) ?: 'BANK'),
            };
        }

        $qrPath = null;
        if ($request->hasFile('qr_image') && $request->file('qr_image')->isValid()) {
            $qrPath = $request->file('qr_image')->store('payments/qris', 'public');
        }

        PaymentMethod::create([
            'code'           => $code,
            'name'           => trim($request->name),
            'type'           => $request->type,
            'bank_code'      => strtoupper(trim($bankCode)),
            'account_number' => $request->account_number ? trim($request->account_number) : null,
            'account_holder' => $request->account_holder ? trim($request->account_holder) : null,
            'qr_image'       => $qrPath,
            'instructions'   => $request->instructions ? trim($request->instructions) : null,
            'is_active'      => $request->has('is_active') ? (bool)$request->is_active : true,
            'sort_order'     => $request->sort_order ?? 0,
        ]);

        return redirect()->route('admin.payment-methods.index')
            ->with('success', 'Metode pembayaran baru berhasil ditambahkan!');
    }

    /**
     * Perbarui metode pembayaran yang ada.
     */
    public function update(Request $request, PaymentMethod $paymentMethod)
    {
        $request->validate([
            'name'           => 'required|string|max:255',
            'type'           => 'required|string|in:qris,bank,ewallet,cash',
            'code'           => ['required', 'string', 'max:50', 'regex:/^[a-zA-Z0-9_\-]+$/', Rule::unique('payment_methods', 'code')->ignore($paymentMethod->id)],
            'bank_code'      => 'nullable|string|max:20',
            'account_number' => 'nullable|string|max:100',
            'account_holder' => 'nullable|string|max:255',
            'qr_image'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'instructions'   => 'nullable|string|max:1000',
            'is_active'      => 'nullable|boolean',
            'sort_order'     => 'nullable|integer|min:0',
        ], [
            'name.required'     => 'Nama metode/bank wajib diisi.',
            'code.required'     => 'Kode unik metode wajib diisi.',
            'code.unique'       => 'Kode unik sudah digunakan oleh metode lain.',
            'code.regex'        => 'Kode unik hanya boleh huruf, angka, tanda minus (-), atau garis bawah (_).',
            'qr_image.image'    => 'File QRIS harus berupa gambar valid.',
            'qr_image.mimes'    => 'Format gambar QRIS yang didukung: JPG, JPEG, PNG, WEBP.',
            'qr_image.max'      => 'Ukuran gambar QRIS maksimal 2 MB agar mudah dimuat penyewa.',
        ]);

        // Cek jika status diubah menjadi non-aktif padahal ini satu-satunya metode aktif
        $willBeActive = $request->has('is_active') ? (bool)$request->is_active : false;
        if (!$willBeActive && $paymentMethod->is_active) {
            $otherActiveCount = PaymentMethod::where('is_active', true)->where('id', '!=', $paymentMethod->id)->count();
            if ($otherActiveCount === 0) {
                return redirect()->back()->withInput()->with('failed', 'Gagal menonaktifkan! Minimal harus ada 1 metode pembayaran yang berstatus aktif di sistem.');
            }
        }

        $qrPath = $paymentMethod->qr_image;
        if ($request->hasFile('qr_image') && $request->file('qr_image')->isValid()) {
            // Hapus gambar lama jika ada
            if ($paymentMethod->qr_image && Storage::disk('public')->exists($paymentMethod->qr_image)) {
                Storage::disk('public')->delete($paymentMethod->qr_image);
            }
            $qrPath = $request->file('qr_image')->store('payments/qris', 'public');
        } elseif ($request->boolean('delete_qr_image')) {
            // Hapus gambar tanpa upload baru
            if ($paymentMethod->qr_image && Storage::disk('public')->exists($paymentMethod->qr_image)) {
                Storage::disk('public')->delete($paymentMethod->qr_image);
            }
            $qrPath = null;
        }

        $bankCode = $request->bank_code;
        if (empty($bankCode)) {
            $bankCode = match ($request->type) {
                'qris'    => 'QRIS',
                'cash'    => 'COD',
                default   => strtoupper(substr(preg_replace('/[^A-Za-z]/', '', $request->name), 0, 4) ?: 'BANK'),
            };
        }

        $paymentMethod->update([
            'code'           => Str::slug($request->code, '_'),
            'name'           => trim($request->name),
            'type'           => $request->type,
            'bank_code'      => strtoupper(trim($bankCode)),
            'account_number' => $request->account_number ? trim($request->account_number) : null,
            'account_holder' => $request->account_holder ? trim($request->account_holder) : null,
            'qr_image'       => $qrPath,
            'instructions'   => $request->instructions ? trim($request->instructions) : null,
            'is_active'      => $willBeActive,
            'sort_order'     => $request->sort_order ?? 0,
        ]);

        return redirect()->route('admin.payment-methods.index')
            ->with('success', "Metode pembayaran '{$paymentMethod->name}' berhasil diperbarui!");
    }

    /**
     * Beralih status aktif / non-aktif (Toggle).
     */
    public function toggle(PaymentMethod $paymentMethod)
    {
        return $this->toggleStatus($paymentMethod);
    }

    public function toggleStatus(PaymentMethod $paymentMethod)
    {
        if ($paymentMethod->is_active) {
            $otherActiveCount = PaymentMethod::where('is_active', true)->where('id', '!=', $paymentMethod->id)->count();
            if ($otherActiveCount === 0) {
                return redirect()->back()->with('failed', 'Tidak dapat menonaktifkan! Minimal harus ada 1 metode pembayaran yang aktif agar penyewa dapat memesan kamar.');
            }
        }

        $paymentMethod->update([
            'is_active' => !$paymentMethod->is_active,
        ]);

        $statusText = $paymentMethod->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return redirect()->back()->with('success', "Metode pembayaran '{$paymentMethod->name}' berhasil {$statusText}!");
    }

    /**
     * Hapus metode pembayaran.
     */
    public function destroy(PaymentMethod $paymentMethod)
    {
        if ($paymentMethod->is_active) {
            $otherActiveCount = PaymentMethod::where('is_active', true)->where('id', '!=', $paymentMethod->id)->count();
            if ($otherActiveCount === 0) {
                return redirect()->back()->with('failed', 'Tidak dapat menghapus! Minimal harus tersisa 1 metode pembayaran yang aktif di sistem.');
            }
        }

        // Hapus file QR code jika ada
        if ($paymentMethod->qr_image && Storage::disk('public')->exists($paymentMethod->qr_image)) {
            Storage::disk('public')->delete($paymentMethod->qr_image);
        }

        $nama = $paymentMethod->name;
        $paymentMethod->delete();

        return redirect()->route('admin.payment-methods.index')
            ->with('success', "Metode pembayaran '{$nama}' berhasil dihapus!");
    }
}
