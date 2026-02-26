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
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            // Liên kết với bảng users (Khách hàng)
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            // Liên kết với bảng games (Sản phẩm)
            $table->foreignId('game_id')->constrained()->onDelete('cascade');
            
            // Cứ thêm sẵn cột quantity lỡ sau này ba muốn bán thêm Máy Console, Tay cầm...
            $table->integer('quantity')->default(1); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carts');
    }
};
