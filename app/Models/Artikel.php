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

    protected $appends = [
        'reading_time',
        'word_count',
        'short_deskripsi',
        'formatted_date',
        'image_url',
    ];

    public function getReadingTimeAttribute(): string
    {
        $words = str_word_count(strip_tags($this->deskripsi ?? ''));
        $minutes = max(1, ceil($words / 180));
        return "{$minutes} mnt baca";
    }

    public function getWordCountAttribute(): int
    {
        return str_word_count(strip_tags($this->deskripsi ?? ''));
    }

    public function getShortDeskripsiAttribute(): string
    {
        return \Illuminate\Support\Str::limit(strip_tags($this->deskripsi ?? ''), 120);
    }

    public function getFormattedDateAttribute(): string
    {
        return $this->created_at ? $this->created_at->isoFormat('D MMM Y') : '-';
    }

    public function getImageUrlAttribute(): ?string
    {
        return $this->image ? asset('storage/' . $this->image) : null;
    }
}
