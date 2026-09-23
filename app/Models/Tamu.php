<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tamu extends Model
{
    protected $fillable = [
        'product_kamar_kosan_id',
        'name',
        'telp',
        'email',
        'start_time',
        'start_date',
        'end_date',
        'payment_method',
        'proof_of_transfer',
        'total_price',
        'status',
        'access_token',
        'processed_by',
        'processed_at',
        'rejection_reason',
    ];

    protected $casts = [
        'processed_at' => 'datetime',
        'start_date' => 'date',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($tamu) {
            if (empty($tamu->access_token)) {
                $tamu->access_token = bin2hex(random_bytes(32));
            }
        });
    }

    public function productKamarKosan(){
        return $this->belongsTo(ProductKamarKosan::class,'product_kamar_kosan_id');
    }

    public function processedBy(){
        return $this->belongsTo(User::class, 'processed_by');
    }

    /**
     * Mengubah format nomor HP lokal Indonesia (08xx, 8xx, +62xx)
     * menjadi standar nomor internasional WhatsApp (628xxx).
     */
    public static function formatToWhatsapp(?string $number): string
    {
        if (!$number) {
            return '';
        }

        // Hapus semua karakter selain angka
        $clean = preg_replace('/[^0-9]/', '', $number);

        // Jika diawali 08 (misal: 08123456789) -> ubah 0 di depan jadi 62
        if (str_starts_with($clean, '0')) {
            $clean = '62' . substr($clean, 1);
        } elseif (str_starts_with($clean, '8')) {
            // Jika user mengetik langsung 812... tanpa 0 dan tanpa 62
            $clean = '62' . $clean;
        }

        return $clean;
    }

    /**
     * Accessor untuk nomor WhatsApp valid internasional (wa.me/{$tamu->whatsapp_number})
     */
    public function getWhatsappNumberAttribute(): string
    {
        return self::formatToWhatsapp($this->telp);
    }

    /**
     * Accessor untuk format tampilan nomor lokal (08xx)
     */
    public function getFormattedTelpAttribute(): string
    {
        $clean = preg_replace('/[^0-9]/', '', $this->telp ?? '');
        if (str_starts_with($clean, '62')) {
            $clean = '0' . substr($clean, 2);
        }
        return $clean ?: ($this->telp ?? '');
    }
}
