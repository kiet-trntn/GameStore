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
        'sale_price', 'image', 'trailer_link', 'category_id', 'is_active'
    ];

    // Quan hệ: Một Game thuộc về một Danh mục
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
