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
        Schema::create('order', function (Blueprint $table) {
            $table->id(); // Order ID
            $table->unsignedBigInteger('customer_id'); // Customer ID from users table
            $table->decimal('total_amount', 10, 2); // Total order amount
            $table->string('method_payment'); // Payment method (e.g., 'cash', 'credit card')
            $table->string('status')->default('pending'); // Order status (e.g., 'pending', 'completed')
            $table->timestamps(); // Created and updated timestamps
        
            // Foreign key constraints
            $table->foreign('customer_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order');
    }
};
