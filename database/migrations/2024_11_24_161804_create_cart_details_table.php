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
        Schema::create('cart_details', function (Blueprint $table) {
            $table->id(); // ID chi tiết giỏ hàng
            $table->unsignedBigInteger('id_cart'); // ID giỏ hàng
            $table->unsignedBigInteger('size_id'); // ID size của sản phẩm
            $table->integer('product_quantity'); // Số lượng sản phẩm
            $table->timestamps();
            // Khóa ngoại liên kết với bảng carts
            $table->foreign('id_cart')->references('id')->on('carts')->onDelete('cascade');
            // Khóa ngoại liên kết với bảng size_codes
            $table->foreign('size_id')->references('id')->on('code_sizes')->onDelete('cascade');
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart_details');
    }
};

