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
        // Only auto-loop in local/dev environment
        $autoLoop = app()->environment('local');

        do {
            $this->info('Fetching WMATA train data...');
            $apiKey = env('WMATA_API_KEY');
            $url = 'https://api.wmata.com/TrainPositions/TrainPositions?contentType=json';

            try {
                $response = Http::withHeaders([
                    'api_key' => $apiKey
                ])->get($url);

                $data = $response->json();

                foreach ($data['TrainPositions'] as $train) {
                    Train::updateOrCreate(
                        ['train_id' => (string) ($train['TrainId'] ?? uniqid('unknown_'))],
                        [
                            'train_number'            => $train['TrainNumber'] ?? null,
                            'car_count'               => $train['CarCount'] ?? null,
                            'direction'               => $train['DirectionNum'] ?? null,
                            'circuit_id'              => $train['CircuitId'] ?? null,
                            'destination_station_code' => $train['DestinationStationCode'] ?? null,
                            'line_code'               => $train['LineCode'] ?? null,
                            'seconds_at_location'     => $train['SecondsAtLocation'] ?? null,
                            'service_type'            => $train['ServiceType'] ?? null,
                            'last_update'             => now(),
                        ]
                    );
                }

                $this->info('WMATA train data updated successfully.');
            } catch (\Exception $e) {
                $this->error('Error fetching WMATA train data: ' . $e->getMessage());
            }

            // Only sleep if we are in dev/local
            if ($autoLoop) {
                sleep(30);
            }
        } while ($autoLoop); // loop forever in local
    }
}
