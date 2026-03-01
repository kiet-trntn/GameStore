<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Game extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title', 'slug', 'description', 'price', 
        'sale_price', 'developer', 'requirements',
        'image', 'screenshots', 'trailer_link', 'category_id', 'is_active',
        'is_featured', 'views', 'release_date',
    ];

    protected $casts = [
        'screenshots' => 'array', // Tự động convert JSON <-> Array
        'requirements' => 'array',
        'release_date' => 'datetime',
    ];

    // Quan hệ: Một Game thuộc về một Danh mục
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function reviews() {
        return $this->hasMany(Review::class)->latest(); // Lấy review mới nhất lên đầu
    }
}
