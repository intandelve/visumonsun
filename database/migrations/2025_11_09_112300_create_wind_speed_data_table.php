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
        // GANTI FUNGSI UP() ANDA DENGAN INI
        Schema::create('wind_speed_data', function (Blueprint $table) {
            $table->id();
            $table->string('month_name'); // e.g., "Jan"
            $table->integer('month_index'); // e.g., 1
            $table->decimal('speed_ms', 8, 2); // e.g., 5.1, 5.3
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wind_speed_data');
    }
};
