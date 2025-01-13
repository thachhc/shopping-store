<?php
use Illuminate\Support\Facades\DB;
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
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        DB::table('categories')->insert([
            ['id' => 1, 'name' => 'Sneakers', 'created_at' => '2024-11-25 13:32:24', 'updated_at' => '2024-12-17 02:16:47'],
            ['id' => 2, 'name' => 'Running', 'created_at' => '2024-11-28 17:18:23', 'updated_at' => '2024-11-28 17:18:23'],
            ['id' => 3, 'name' => 'Basketball', 'created_at' => '2024-11-28 17:18:48', 'updated_at' => '2024-11-28 17:18:48'],
            ['id' => 4, 'name' => 'Training', 'created_at' => '2024-11-28 17:18:51', 'updated_at' => '2024-11-28 17:18:51'],
            ['id' => 5, 'name' => 'Fashion', 'created_at' => '2024-11-28 17:18:55', 'updated_at' => '2024-11-28 17:18:55'],
            ['id' => 6, 'name' => 'Hiking', 'created_at' => '2024-11-28 17:19:00', 'updated_at' => '2024-11-28 17:19:00'],
            ['id' => 7, 'name' => 'Boots', 'created_at' => '2024-11-28 17:19:34', 'updated_at' => '2024-11-28 17:19:34'],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
