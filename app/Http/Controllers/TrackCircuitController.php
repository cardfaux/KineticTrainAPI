<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TrackCircuit;
use Illuminate\Support\Facades\Http;

class TrackCircuitController extends Controller
{
    private $apiKey;

    public function __construct()
    {
        $this->apiKey = config('services.wmata.key'); // put your key in config/services.php
    }

    /**
     * Fetch track circuits from WMATA API and store locally
     */
    public function fetchFromApi()
    {
        $apiKey = env('WMATA_API_KEY');

        if (!$apiKey) {
            $this->error('WMATA_API_KEY is not set in .env');
            return 1;
        }

        $response = Http::withHeaders([
            'api_key' => $apiKey
        ])->get('https://api.wmata.com/TrainPositions/TrackCircuits?contentType=json');

        if ($response->failed()) {
            return response()->json(['error' => 'Failed to fetch track circuits'], 500);
        }

        $trackCircuits = $response->json('TrackCircuits');

        foreach ($trackCircuits as $circuit) {
            TrackCircuit::updateOrCreate(
                ['circuit_id' => $circuit['CircuitId']],
                [
                    'track' => $circuit['Track'],
                    'neighbors' => json_encode($circuit['Neighbors']),
                ]
            );
        }

        return response()->json(['message' => 'Track circuits updated', 'count' => count($trackCircuits)]);
    }

    /**
     * Return all track circuits
     */
    public function index()
    {
        return TrackCircuit::all();
    }

    public function show($circuitId)
    {
        $circuit = TrackCircuit::where('circuit_id', $circuitId)->firstOrFail();

        return response()->json($circuit);
    }
}
