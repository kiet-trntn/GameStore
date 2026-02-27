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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            $table->string('order_code')->unique(); // Mã đơn hàng (VD: GAMEX-12345)
            $table->integer('total_amount'); // Tổng tiền lúc thanh toán
            $table->string('payment_method')->default('Chuyển khoản'); // Phương thức TT
            $table->string('status')->default('completed'); // Trạng thái (Digital thường mua xong là có game luôn)
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
