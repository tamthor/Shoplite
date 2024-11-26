<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('address_id')->constrained('addresses')->onDelete('cascade'); // Liên kết đến bảng addresses
            $table->foreignId('pet_id')->constrained('pets')->onDelete('cascade'); // Liên kết đến bảng pets
            $table->decimal('pet_price', 10, 2); // Giá sản phẩm
            $table->unsignedInteger('quantity');
            $table->decimal('shipping_cost', 10, 2)->default(0); // Phí vận chuyển
            $table->decimal('tax', 10, 2)->default(0); // Thuế
            $table->decimal('total', 10, 2); // Tổng tiền
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_details');
    }
}