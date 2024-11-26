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
        Schema::create('pets', function (Blueprint $table) {
            $table->id(); // ID chính, auto increment
            $table->string('name'); // Tên thú cưng
            $table->string('type'); // Loại thú cưng (vd: Dog, Cat)
            $table->unsignedBigInteger('category_id'); // Khóa ngoại tới bảng pets_category
            $table->foreign('category_id')->references('id')->on('pets_category')->onDelete('cascade'); // Thiết lập khóa ngoại
            $table->text('description')->nullable(); // Mô tả thú cưng
            $table->decimal('price', 10, 2); // Giá thú cưng (vd: 999.99)
            $table->string('image')->nullable(); // Ảnh thú cưng
            $table->timestamps(); // Cột created_at và updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pets'); // Xóa bảng nếu rollback
    }
};
