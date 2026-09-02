<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Artikel extends Model
{
    use HasFactory;

    protected $table = 'artikels';

    protected static function boot()
    {
        parent::boot();
        static::deleting(function($image){
            if($image->image && Storage::disk('public')->exists($image->image)){
                Storage::disk('public')->delete($image->image);
            }
        });
    }

    protected $fillable = [
        'title',
        'slug',
        'image',
        'deskripsi',
        'view',
    ];
}
