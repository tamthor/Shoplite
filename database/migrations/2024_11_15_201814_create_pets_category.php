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
        Schema::create('pets_category', function (Blueprint $table) {
            $table->id(); // ID chính, auto increment
            $table->string('name'); // Tên danh mục
            $table->text('description')->nullable(); // Mô tả danh mục, cho phép NULL
            $table->string('image')->nullable(); // Hình ảnh minh họa danh mục, cho phép NULL
            $table->timestamps(); // Cột created_at và updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pets_category'); // Xóa bảng nếu rollback
    }
};
