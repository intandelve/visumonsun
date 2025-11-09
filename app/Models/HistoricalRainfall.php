<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoricalRainfall extends Model
{
    use HasFactory;

    // ▼▼▼ TAMBAHKAN BARIS INI ▼▼▼
    // Beri tahu Model ini untuk menggunakan tabel 'historical_rainfall_data'
    protected $table = 'historical_rainfall_data';
}