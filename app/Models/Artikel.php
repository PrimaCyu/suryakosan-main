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
        'parsed_deskripsi',
    ];

    /**
     * Sanitize HTML content to prevent XSS while allowing rich text formatting.
     */
    public static function sanitizeHtml(?string $html): string
    {
        if (empty($html)) {
            return '';
        }

        // If content has no HTML tags, treat as plain text and preserve newlines
        if (strip_tags($html) === $html) {
            return nl2br(e($html));
        }

        // Remove dangerous script/style/iframe/applet/object/form tags
        $dangerousTags = ['script', 'style', 'iframe', 'object', 'embed', 'applet', 'form'];
        foreach ($dangerousTags as $tag) {
            $html = preg_replace('#<' . $tag . '[^>]*>.*?</' . $tag . '>#is', '', $html);
            $html = preg_replace('#<' . $tag . '[^>]*>#is', '', $html);
        }

        // Remove inline event handlers (onload, onerror, onclick, etc.)
        $html = preg_replace('/(\s+)on[a-z]+\s*=\s*(["\'])(.*?)\2/i', '', $html);
        $html = preg_replace('/(\s+)on[a-z]+\s*=\s*([^\s>]+)/i', '', $html);

        // Remove javascript: and vbscript: URIs in links
        $html = preg_replace('/href\s*=\s*(["\'])\s*(javascript|vbscript):.*?\1/i', 'href="#"', $html);

        // Clean up proprietary/scraped AI attributes (like Google Gemini _ngcontent, data-index-in-node)
        $html = preg_replace('/\s*(_ngcontent[^\s=>]*|data-index-in-node|data-path-to-node)(="[^"]*")?/i', '', $html);

        // Clean aggressive inline styles copied from other sites or AI tools (font-family, cramped line-height, css variables)
        $html = preg_replace_callback('/style\s*=\s*(["\'])(.*?)\1/is', function ($matches) {
            $style = $matches[2];
            $style = preg_replace('/font-family\s*:\s*[^;"]+;?/i', '', $style);
            $style = preg_replace('/line-height\s*:\s*[^;"]+;?/i', '', $style);
            $style = preg_replace('/--[a-zA-Z0-9\-]+\s*:\s*[^;"]+;?/i', '', $style);
            $style = trim(trim($style), ';');
            return !empty($style) ? 'style="' . $style . '"' : '';
        }, $html);

        return trim($html);
    }

    public function getParsedDeskripsiAttribute(): string
    {
        return self::sanitizeHtml($this->deskripsi);
    }

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
