<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PriceKamar extends Model
{
    protected $fillable = ['product_kamar_kosan_id','kategori','price','discount'];

    public function productKamarKosan(){
        return $this->belongsTo(ProductKamarKosan::class,'product_kamar_kosan_id');
    }
    
}
