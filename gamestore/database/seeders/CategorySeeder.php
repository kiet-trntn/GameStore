<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category; // Nhớ use Model Category vô nha
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run()
    {
        // Danh sách các thể loại game kinh điển
        $categories = [
            'Hành động - Phiêu lưu',
            'Nhập vai (RPG)',
            'Bắn súng (FPS)',
            'Thể thao - Đua xe',
            'Chiến thuật',
            'Sinh tồn - Kinh dị',
            'Thế giới mở'
        ];

        foreach ($categories as $catName) {
            Category::create([
                'name' => $catName,
                'slug' => Str::slug($catName), // Tự động biến "Hành động" -> "hanh-dong"
                'is_active' => true,
                // Chỗ này image tạm thời để null, sau này ba vô Admin up ảnh lên sau
                'image' => null 
            ]);
        }
    }
}