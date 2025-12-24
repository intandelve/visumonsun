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
        Schema::create('wind_speed_data', function (Blueprint $table) {
            $table->id();
            $table->string('month_name'); 
            $table->integer('month_index'); 
            $table->decimal('speed_ms', 8, 2); 
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
