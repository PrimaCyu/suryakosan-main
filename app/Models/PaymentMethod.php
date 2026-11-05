<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class PaymentMethod extends Model
{
    protected $table = 'payment_methods';

    protected $fillable = [
        'code',
        'name',
        'type',
        'bank_code',
        'account_number',
        'account_holder',
        'qr_image',
        'instructions',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active'  => 'boolean',
        'sort_order' => 'integer',
    ];

    protected $appends = [
        'qr_image_url',
        'type_label',
    ];

    /**
     * Scope untuk mengambil metode pembayaran yang aktif.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope untuk mengurutkan berdasarkan urutan tampilan.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order', 'asc')->orderBy('id', 'asc');
    }

    /**
     * Mendapatkan URL gambar QR code jika ada.
     */
    public function getQrImageUrlAttribute(): ?string
    {
        if ($this->qr_image && Storage::disk('public')->exists($this->qr_image)) {
            return asset('storage/' . $this->qr_image);
        }

        return null;
    }

    /**
     * Label tipe yang ramah pengguna.
     */
    public function getTypeLabelAttribute(): string
    {
        return match ($this->type) {
            'qris'    => 'QRIS (Semua Pembayaran)',
            'bank'    => 'Transfer Bank',
            'ewallet' => 'E-Wallet',
            'cash'    => 'Tunai / COD',
            default   => ucfirst($this->type),
        };
    }
}

