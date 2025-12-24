<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WindMapData extends Model
{
    use HasFactory;

    // name table in db is 'wind_map_data'
    protected $table = 'wind_map_data';

    // convert data json_data to array
    protected $casts = [
        'json_data' => 'array',
    ];
}