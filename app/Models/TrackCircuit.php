<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrackCircuit extends Model
{
    protected $fillable = [
        'circuit_id',
        'track',
        'neighbors',
    ];

    protected $casts = [
        'neighbors' => 'array', // Automatically decode JSON
    ];

    // Optionally, get left/right neighbors as arrays
    public function leftNeighbors()
    {
        return collect($this->neighbors)
            ->where('NeighborType', 'Left')
            ->pluck('CircuitIds')
            ->flatten()
            ->toArray();
    }

    public function rightNeighbors()
    {
        return collect($this->neighbors)
            ->where('NeighborType', 'Right')
            ->pluck('CircuitIds')
            ->flatten()
            ->toArray();
    }
}
