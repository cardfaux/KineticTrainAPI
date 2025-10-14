<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Station extends Model
{
    use HasFactory;

    // Which fields are mass assignable
    protected $fillable = [
        'station_code',
        'name',
        'lat',
        'lng',
        'lines',
    ];

    // Cast lines to array automatically
    protected $casts = [
        'lines' => 'array',
        'lat' => 'float',
        'lng' => 'float',
    ];
}
