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
        Schema::create('brands', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamps();
        });
        DB::table('brands')->insert([
            [
                'id' => 1,
                'name' => 'NIKE',
                'description' => 'Just do it',
                'created_at' => '2024-11-25 13:33:19',
                'updated_at' => '2024-11-28 17:12:43',
            ],
            [
                'id' => 2,
                'name' => 'ADIDAS',
                'description' => 'Impossible is nothing',
                'created_at' => '2024-11-28 17:12:30',
                'updated_at' => '2024-11-28 17:12:49',
            ],
            [
                'id' => 3,
                'name' => 'PUMA',
                'description' => 'Forever Faster',
                'created_at' => '2024-11-28 17:13:01',
                'updated_at' => '2024-11-28 17:13:01',
            ],
            [
                'id' => 4,
                'name' => 'REEBOK',
                'description' => 'Running the show: Reebok',
                'created_at' => '2024-11-28 17:13:10',
                'updated_at' => '2024-11-28 17:13:10',
            ],
            [
                'id' => 5,
                'name' => 'NEW BALANCE',
                'description' => 'Endorsed by No One',
                'created_at' => '2024-11-28 17:13:37',
                'updated_at' => '2024-11-28 17:13:37',
            ],
            [
                'id' => 6,
                'name' => 'CONVERSE',
                'description' => 'WE ARE NOT ALONE',
                'created_at' => '2024-11-28 17:13:47',
                'updated_at' => '2024-11-28 17:13:47',
            ],
            [
                'id' => 7,
                'name' => 'VANS',
                'description' => 'Off The Wall',
                'created_at' => '2024-11-28 17:13:57',
                'updated_at' => '2024-11-28 17:13:57',
            ],
            [
                'id' => 8,
                'name' => 'SKECHERS',
                'description' => 'Good for your feet. Good for the world',
                'created_at' => '2024-11-28 17:14:07',
                'updated_at' => '2024-11-28 17:14:07',
            ],
        ]);
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('brands');
    }
};
