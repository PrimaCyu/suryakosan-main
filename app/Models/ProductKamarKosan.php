<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductKamarKosan extends Model
{
    protected $fillable = ['product_kosan_id','room','description','fasilitas','cumulative_discount','gmaps','views'];
    protected $touches = ['productKosan'];
    protected $appends = ['room_status', 'primary_image'];

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($kamar) {
            foreach ($kamar->productKamarImageKosan as $kamarImage) {
                $kamarImage->delete();
            }
        });
    }

    public function productKosan(){
        return $this->belongsTo(ProductKosan::class,'product_kosan_id');
    }

    public function productKamarImageKosan(){
        return $this->hasMany(ProductKamarImageKosan::class);
    }

    public function priceKamar(){
        return $this->hasMany(PriceKamar::class);
    }

    public function tamu(){
        return $this->hasMany(Tamu::class,'product_kamar_kosan_id');
    }

    /**
     * Penyewa aktif (status approved dan masa sewa masih berlaku).
     */
    public function getActiveTenantAttribute()
    {
        if ($this->relationLoaded('tamu')) {
            return $this->tamu
                ->where('status', 'approved')
                ->filter(function ($t) {
                    try {
                        return \Carbon\Carbon::parse($t->end_date)->endOfDay()->gte(now());
                    } catch (\Exception $e) {
                        return false;
                    }
                })
                ->sortByDesc('end_date')
                ->first();
        }

        return $this->tamu()
            ->where('status', 'approved')
            ->whereDate('end_date', '>=', now()->toDateString())
            ->orderByDesc('end_date')
            ->first();
    }

    /**
     * Booking pending (menunggu verifikasi/persetujuan admin).
     */
    public function getPendingTenantAttribute()
    {
        if ($this->relationLoaded('tamu')) {
            return $this->tamu->where('status', 'pending')->sortByDesc('created_at')->first();
        }

        return $this->tamu()->where('status', 'pending')->orderByDesc('created_at')->first();
    }

    /**
     * Status ketersediaan kamar riil: 'terisi' | 'pending' | 'kosong'.
     */
    public function getRoomStatusAttribute(): string
    {
        if ($this->active_tenant) {
            return 'terisi';
        }
        if ($this->pending_tenant) {
            return 'pending';
        }
        return 'kosong';
    }

    /**
     * Data harga bulanan.
     */
    public function getMonthlyPriceAttribute()
    {
        if ($this->relationLoaded('priceKamar')) {
            return $this->priceKamar->where('kategori', 'bulan')->first();
        }
        return $this->priceKamar()->where('kategori', 'bulan')->first();
    }

    /**
     * Data harga tahunan.
     */
    public function getYearlyPriceAttribute()
    {
        if ($this->relationLoaded('priceKamar')) {
            return $this->priceKamar->where('kategori', 'tahun')->first();
        }
        return $this->priceKamar()->where('kategori', 'tahun')->first();
    }

    /**
     * Foto utama kamar.
     */
    public function getPrimaryImageAttribute()
    {
        if ($this->relationLoaded('productKamarImageKosan')) {
            return $this->productKamarImageKosan->first();
        }
        return $this->productKamarImageKosan()->first();
    }
}
