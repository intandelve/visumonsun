<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('historical_rainfall_data', function (Blueprint $table) {
            $table->id();
            $table->integer('year'); 
            $table->integer('month_index'); 
            $table->string('month_name'); 
            $table->decimal('rainfall_mm', 8, 2);
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('historical_rainfall_data');
    }
};