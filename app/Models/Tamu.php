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
        'status'
    ];

    public function productKamarKosan(){
        return $this->belongsTo(ProductKamarKosan::class,'product_kamar_kosan_id');
    }
}
