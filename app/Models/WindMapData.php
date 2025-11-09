<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WindMapData extends Model
{
    use HasFactory;

    // Beri tahu Model nama tabel yang benar
    protected $table = 'wind_map_data';

    // Ini "sihir" Laravel: Otomatis ubah kolom 'json_data'
    // dari teks (string) menjadi array/object saat diambil
    protected $casts = [
        'json_data' => 'array',
    ];
}