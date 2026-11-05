<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ProductKamarImageKosan extends Model
{
    protected $fillable = ['product_kamar_kosan_id','image'];
    protected $touches = ['productKamarKosan'];

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($imageModel) {
            if ($imageModel->image && Storage::disk('public')->exists($imageModel->image)) {
                Storage::disk('public')->delete($imageModel->image);
            }
        });
    }

    public function productKamarKosan(){
        return $this->belongsTo(ProductKamarKosan::class,'product_kamar_kosan_id');
    }
}
