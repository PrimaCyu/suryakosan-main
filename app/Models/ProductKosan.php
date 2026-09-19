<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductKosan extends Model
{
    protected $fillable =
    [
        'title',
        'slug',
        'description',
        'fasilitas',
        'wilayah',
        'view',
        'tersedia',
        'gmaps'
    ];

    protected static function boot(){
        parent::boot();

        static::deleting(function ($kosan) {
            foreach ($kosan->productImageKosan as $image) {
                $image->delete();
            }

            foreach ($kosan->productKamarKosan as $kamar) {
                $kamar->delete();
            }
        });
    }

    public function productImageKosan(){
        return $this->hasMany(ProductImageKosan::class);
    }

    public function productKamarKosan(){
        return $this->hasMany(ProductKamarKosan::class);
    }

    public function admins(){
        return $this->belongsToMany(User::class, 'admin_kosan', 'product_kosan_id', 'user_id')->withTimestamps();
    }

    /**
     * Hitung ulang dan sinkronkan kolom tersedia di database dengan data kamar riil.
     */
    public function syncAvailableCount(): int
    {
        $totalRooms = $this->productKamarKosan()->count();
        if ($totalRooms === 0) {
            return (int) ($this->tersedia ?? 0);
        }

        $occupiedRooms = $this->productKamarKosan()
            ->whereHas('tamu', function ($q) {
                $q->where('status', 'approved')
                  ->whereDate('end_date', '>=', now()->toDateString());
            })
            ->count();

        $available = max(0, $totalRooms - $occupiedRooms);
        $this->update(['tersedia' => $available]);
        return $available;
    }

    /**
     * Accessor untuk total kapasitas kamar riil.
     */
    public function getTotalRoomsCountAttribute(): int
    {
        if ($this->relationLoaded('productKamarKosan')) {
            return $this->productKamarKosan->count();
        }
        return $this->productKamarKosan()->count();
    }

    /**
     * Accessor untuk jumlah kamar terisi aktif.
     */
    public function getOccupiedRoomsCountAttribute(): int
    {
        if ($this->relationLoaded('productKamarKosan')) {
            return $this->productKamarKosan->filter(function ($kamar) {
                if ($kamar->relationLoaded('tamu')) {
                    return $kamar->tamu->where('status', 'approved')->where('end_date', '>=', now()->toDateString())->count() > 0;
                }
                return $kamar->tamu()->where('status', 'approved')->whereDate('end_date', '>=', now()->toDateString())->exists();
            })->count();
        }

        return $this->productKamarKosan()
            ->whereHas('tamu', function ($q) {
                $q->where('status', 'approved')
                  ->whereDate('end_date', '>=', now()->toDateString());
            })
            ->count();
    }

    /**
     * Accessor untuk jumlah kamar kosong riil.
     */
    public function getAvailableRoomsCountAttribute(): int
    {
        $total = $this->total_rooms_count;
        if ($total === 0) {
            return (int) ($this->tersedia ?? 0);
        }
        return max(0, $total - $this->occupied_rooms_count);
    }

    /**
     * Accessor persentase tingkat okupansi.
     */
    public function getOccupancyRateAttribute(): float
    {
        $total = $this->total_rooms_count;
        if ($total === 0) return 0.0;
        return round(($this->occupied_rooms_count / $total) * 100, 1);
    }
}
