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
    Schema::create('rainfall_data', function (Blueprint $table) {
        $table->id();
        $table->string('month_name'); // e.g., "Jan", "Feb"
        $table->integer('month_index'); // e.g., 1, 2, 3
        $table->decimal('rainfall_mm', 8, 2); // e.g., 340.50
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rainfall_data');
    }
};
