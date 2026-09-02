<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductKamarKosan extends Model
{
    protected $fillable = ['product_kosan_id','room','description','fasilitas','cumulative_discount','gmaps','views'];

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
}
