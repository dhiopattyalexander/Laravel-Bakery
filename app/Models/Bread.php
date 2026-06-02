<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bread extends Model
{
    // Mematikan timestamps otomatis karena tabelmu tidak punya updated_at
    public $timestamps = false; 

    // IZINKAN LARAVEL MENGISI KOLOM-KOLOM INI:
    protected $fillable = [
        'category_id',
        'name',
        'description',
        'image_path',
        'price',
        'stock',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public static function getImageUrl(?string $imagePath): string
    {
        if (empty($imagePath)) {
            return asset('images/roti-placeholder.svg');
        }
        if (\Illuminate\Support\Str::startsWith($imagePath, 'images/')) {
            return asset($imagePath);
        }
        if (\Illuminate\Support\Str::startsWith($imagePath, 'http://') || \Illuminate\Support\Str::startsWith($imagePath, 'https://')) {
            return $imagePath;
        }
        return asset('storage/' . $imagePath);
    }

    public function getImageUrlAttribute(): string
    {
        return self::getImageUrl($this->image_path);
    }
}