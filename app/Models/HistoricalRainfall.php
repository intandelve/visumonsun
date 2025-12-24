<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoricalRainfall extends Model
{
    use HasFactory;

    protected $table = 'historical_rainfall_data';
}