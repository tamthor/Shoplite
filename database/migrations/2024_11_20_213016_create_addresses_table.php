<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();  // Khóa chính tự tăng
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');  // Khóa ngoại liên kết với bảng users
            $table->string('address_line1');  // Địa chỉ chính
            $table->string('address_line2')->nullable();  // Địa chỉ phụ (có thể rỗng)
            $table->string('city');  // Thành phố
            $table->string('state');  // Tỉnh/Thành phố
            $table->string('postal_code');  // Mã bưu điện
            $table->string('phone_number')->nullable();  // Số điện thoại (có thể rỗng)
            $table->boolean('is_default')->default(false);  // Đánh dấu địa chỉ mặc định
            $table->timestamps();  // Thời gian tạo và cập nhật (created_at, updated_at)
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('addresses');
    }
}
