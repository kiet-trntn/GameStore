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
        Schema::create('games', function (Blueprint $table) {
            $table->id();
            $table->string('title'); 
            $table->string('slug')->unique();
            $table->decimal('price', 15, 2);
            $table->foreignId('category_id')->constrained('categories'); // Khóa ngoại
            $table->softDeletes();
            // Tạm thời để mấy cái này là nullable để không bị lỗi lúc lưu
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->string('trailer_link')->nullable();
            
            $table->boolean('is_active')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('games');
    }
};
