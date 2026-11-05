<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FasilitasKamar extends Model
{
    use HasFactory;

    protected $table = 'fasilitas_kamars';

    protected $fillable = [
        'product_kamar_kosan_id',
        'title',
    ];

    protected $touches = ['productKamarKosan'];

    public function productKamarKosan()
    {
        return $this->belongsTo(ProductKamarKosan::class, 'product_kamar_kosan_id');
    }
}
