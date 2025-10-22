<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Train;

class FetchWmataTrainData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wmata:fetch-train-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch live WMATA train positions and store in database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Force load .env
        $dotenv = \Dotenv\Dotenv::createImmutable(base_path());
        $dotenv->load();

        $apiKey = env('WMATA_API_KEY');
        if (!$apiKey) {
            $this->error('WMATA_API_KEY is not set in .env');
            return 1;
        }

        $response = Http::withHeaders(['api_key' => $apiKey])
            ->get('https://api.wmata.com/TrainPositions/TrainPositions?contentType=json');

        if ($response->failed()) {
            $this->error('Error fetching WMATA train data.');
            return 1;
        }

        foreach ($response->json('TrainPositions', []) as $train) {
            Train::updateOrCreate(
                ['train_id' => (string) ($train['TrainId'] ?? uniqid('unknown_'))],
                [
                    'train_number' => $train['TrainNumber'] ?? null,
                    'car_count' => $train['CarCount'] ?? null,
                    'direction' => $train['DirectionNum'] ?? null,
                    'circuit_id' => $train['CircuitId'] ?? null,
                    'destination_station_code' => $train['DestinationStationCode'] ?? null,
                    'line_code' => $train['LineCode'] ?? null,
                    'seconds_at_location' => $train['SecondsAtLocation'] ?? null,
                    'service_type' => $train['ServiceType'] ?? null,
                    'last_update' => now(),
                ]
            );
        }

        $this->info('WMATA train data updated successfully.');
        return 0;
    }
}
