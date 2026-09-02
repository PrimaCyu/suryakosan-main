<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ProductImageKosan extends Model
{
    protected $fillable = ['product_kosan_id','image'];

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($imageModel) {
            if ($imageModel->image && Storage::disk('public')->exists($imageModel->image)) {
                Storage::disk('public')->delete($imageModel->image);
            }
        });
    }

    public function productKosan(){
        return $this->belongsTo(ProductKosan::class,'product_kosan_id');
    }
}
