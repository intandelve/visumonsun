<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WindSpeedData extends Model
{
    use HasFactory;

    protected $table = 'wind_speed_data';

    protected $fillable = [
        'month_name',
        'month_index',
        'speed_ms',
    ];
}
