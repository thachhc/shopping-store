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
            $table->id(); // Auto-incrementing primary key
            $table->foreignId('order_id')->constrained('order')->onDelete('cascade'); // Adjusted to use 'order' as the table name
            $table->foreignId('id_cart_detail')->constrained('cart_details')->onDelete('cascade'); // Foreign key to `cart_details` table
            // $table->decimal('total_amount', 10, 2); // Total amount for this order detail
            // $table->string('method_payment'); // Payment method used
            $table->timestamps(); // Created at & Updated at timestamps
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
