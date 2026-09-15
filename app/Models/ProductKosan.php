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
}
