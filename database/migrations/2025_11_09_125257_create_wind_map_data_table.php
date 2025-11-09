<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wind_map_data', function (Blueprint $table) {
            $table->id();
            $table->string('data_type'); // e.g., 'current_wind'
            $table->json('json_data'); // Kolom tipe JSON untuk data peta
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('wind_map_data');
    }
};