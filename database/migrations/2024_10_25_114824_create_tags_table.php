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
        Schema::create('tags', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });
        
        DB::table('tags')->insert([
            [
                'id' => 2,
                'name' => 'None',
                'created_at' => '2024-11-28 17:17:30',
                'updated_at' => '2024-11-28 17:17:30',
            ],
            [
                'id' => 3,
                'name' => 'New Arrivals',
                'created_at' => '2024-11-28 17:18:02',
                'updated_at' => '2024-11-28 17:18:02',
            ],
            [
                'id' => 5,
                'name' => 'Sale',
                'created_at' => '2025-01-01 23:48:42',
                'updated_at' => '2025-01-01 23:48:42',
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tags');
    }
};
