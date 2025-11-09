<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('forecasts', function (Blueprint $table) {
            $table->id();
            $table->string('data_type'); // e.g., 'seasonal_outlook'
            $table->string('title'); // e.g., 'Seasonal Outlook (Next 3 Months)'
            $table->text('content'); // Teks prakiraannya
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('forecasts');
    }
};