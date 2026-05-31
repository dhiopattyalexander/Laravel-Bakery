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
}