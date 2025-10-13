<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Train extends Model
{
    use HasFactory;

    protected $fillable = [
        'train_id',
        'train_number',
        'car_count',
        'direction',
        'circuit_id',
        'destination_station_code',
        'line_code',
        'seconds_at_location',
        'service_type',
        'last_update',
        'name',
        'status',
        'origin',
        'destination',
    ];

    protected $casts = [
        'last_update' => 'datetime',
    ];
}
