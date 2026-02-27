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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade'); // Thuộc hóa đơn nào
            $table->foreignId('game_id')->constrained()->onDelete('cascade'); // Mua game gì
            
            $table->integer('price'); // Ghi lại giá tiền TẠI THỜI ĐIỂM MUA (Cực kỳ quan trọng)
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
