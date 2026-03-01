<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // Tiêu đề bài viết
            $table->string('slug')->unique(); // Đường dẫn thân thiện (VD: ban-cap-nhat-2)
            $table->string('category')->default('Tin tức'); // Thể loại (Sự kiện, Phần cứng, Cập nhật...)
            $table->string('image')->nullable(); // Link ảnh bìa
            $table->text('summary')->nullable(); // Đoạn tóm tắt ngắn ngoài Trang chủ
            $table->longText('content')->nullable(); // Nội dung chi tiết bài viết (HTML)
            $table->boolean('is_published')->default(true); // Trạng thái Ẩn/Hiện
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
